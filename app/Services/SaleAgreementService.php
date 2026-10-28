<?php

namespace App\Services;

use App\Mail\CustomerWelcomeMail;
use App\Mail\SaleAgreementMail;
use App\Mail\SalePaidNotification;
use App\Models\AdminUser;
use App\Models\Lead;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sale;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * Everything that happens to a sale after the form is submitted, in one place,
 * so the admin portal and SP SALES behave exactly the same:
 *   capture -> PDF agreement -> email customer (+ copies) -> online acceptance
 *   -> secure payment link -> payment cleared -> customer account + plan active.
 */
class SaleAgreementService
{
    /** Where internal copies go: the same address the contact form uses. */
    public function adminAddress(): string
    {
        return env('LEAD_NOTIFY_ADDRESS') ?: config('mail.lead_notify_address', 'admin@nomorepest.co.za');
    }

    /**
     * Default monthly subscription and activation fee per service, from the live
     * Plans & Pricing table, so the form pre-fills with today's prices.
     */
    public function pricingDefaults(): array
    {
        $plans = Plan::query()->get()->keyBy('key');

        $priceFor = function (array $keys) use ($plans): array {
            $monthly = 0.0;
            $activation = 0.0;
            foreach ($keys as $key) {
                $plan = $plans->get($key);
                if (! $plan) {
                    continue;
                }
                $meta = is_array($plan->meta) ? $plan->meta : (json_decode((string) $plan->meta, true) ?: []);
                $monthly += (float) $plan->price;
                $activation += (float) ($meta['initial_fee'] ?? 0);
            }

            return ['monthly' => round($monthly, 2), 'activation' => round($activation, 2)];
        };

        $defaults = [];
        foreach (Sale::SERVICE_PLAN_KEYS as $service => $keys) {
            $defaults[$service] = $priceFor($keys);
        }

        return $defaults;
    }

    /** Questionnaire answers pre-filled from a lead, so nothing is typed twice. */
    public function prefillFromLead(?Lead $lead): array
    {
        if (! $lead) {
            return [];
        }

        $property = match (strtolower((string) $lead->property_type)) {
            'commercial', 'industrial' => 'business',
            'estate' => 'townhouse',
            default => null,
        };

        $pest = strtolower((string) $lead->pest_type);
        $service = match (true) {
            str_contains($pest, 'roach') && (str_contains($pest, 'rat') || str_contains($pest, 'rodent') || str_contains($pest, 'mice')) => 'both',
            str_contains($pest, 'roach') => 'cockroach',
            str_contains($pest, 'rat') || str_contains($pest, 'rodent') || str_contains($pest, 'mice') => 'rodent',
            default => null,
        };

        return array_filter([
            'customer_name' => $lead->name,
            'contact_person' => $lead->name,
            'phone' => $lead->phone,
            'email' => $lead->email,
            'service_address' => $lead->address,
            'suburb' => $lead->suburb,
            'property_type' => $property,
            'service' => $service,
        ], fn ($value) => $value !== null && $value !== '');
    }

    /** Save the sale, build the agreement PDF and send it. */
    public function create(array $data, ?User $rep, ?AdminUser $admin, ?Lead $lead): Sale
    {
        $sale = DB::transaction(function () use ($data, $rep, $admin, $lead) {
            $sale = Sale::create([
                'lead_id' => $lead?->id,
                'sales_rep_id' => $rep?->id,
                'captured_by_admin' => $admin?->id,
                'customer_name' => $data['customer_name'],
                'contact_person' => $data['contact_person'],
                'phone' => $data['phone'],
                'email' => strtolower($data['email']),
                'service_address' => $data['service_address'],
                'suburb' => $data['suburb'],
                'property_type' => $data['property_type'],
                'service' => $data['service'],
                'children_or_pets' => $data['children_or_pets'] === 'yes',
                'bait_stations' => in_array($data['service'], ['rodent', 'both'], true) ? (int) $data['bait_stations'] : null,
                'treatment_areas' => in_array($data['service'], ['cockroach', 'both'], true) ? $data['treatment_areas'] : null,
                'agreement_date' => $data['agreement_date'],
                'inception_date' => $data['inception_date'],
                'activation_fee' => $data['activation_fee'] ?? 0,
                'monthly_subscription' => $data['monthly_subscription'],
                'payment_frequency' => $data['payment_frequency'],
                'status' => 'sent',
                'token' => Str::random(48),
            ]);

            // The lead now has a quote out; only a cleared payment marks it converted.
            if ($lead && ! in_array($lead->status, ['converted'], true)) {
                $lead->update(['status' => 'quoted']);
            }

            return $sale;
        });

        $this->safeGeneratePdf($sale);
        $this->sendAgreement($sale);

        return $sale->fresh();
    }

    /** (Re)build the agreement PDF. Called on capture and again once accepted, so the PDF shows the acceptance. */
    public function generatePdf(Sale $sale): void
    {
        $sale->loadMissing('salesRep');

        $pdf = Pdf::loadView('pdfs.service-agreement', [
            'sale' => $sale,
            'logo' => $this->logoDataUri(),
        ])->setPaper('a4');

        $path = 'agreements/'.$sale->id.'/'.$sale->pdfFileName();
        Storage::disk('local')->put($path, $pdf->output());

        $sale->forceFill(['agreement_path' => $path])->save();
    }

    /** A PDF problem must never lose the sale: log it, and the email still goes out with the online link. */
    private function safeGeneratePdf(Sale $sale): void
    {
        try {
            $this->generatePdf($sale);
        } catch (\Throwable $e) {
            Log::error('Agreement PDF could not be generated', ['sale_id' => $sale->id, 'error' => $e->getMessage()]);
        }
    }

    /** Email the agreement to the customer, with copies to admin and the rep. Safe to call again to resend. */
    public function sendAgreement(Sale $sale): bool
    {
        $stage = $sale->isAwaitingPayment() ? 'accepted' : 'sent';

        $sent = $this->deliver($sale->email, new SaleAgreementMail($sale, $stage, false), $sale, 'agreement to customer');
        $this->sendInternalCopies($sale, new SaleAgreementMail($sale, $stage, true));

        if ($sent && $stage === 'sent') {
            $sale->forceFill(['sent_at' => now()])->save();
        }

        return $sent;
    }

    /** The customer accepted online: record it, update the PDF and send the payment link. */
    public function accept(Sale $sale, string $typedName, ?string $ip, ?string $userAgent): void
    {
        $sale->forceFill([
            'status' => 'accepted',
            'accepted_at' => now(),
            'accepted_name' => $typedName,
            'accepted_ip' => $ip,
            'accepted_user_agent' => Str::limit((string) $userAgent, 490, ''),
        ])->save();

        $this->safeGeneratePdf($sale);

        $this->deliver($sale->email, new SaleAgreementMail($sale, 'accepted', false), $sale, 'accepted agreement to customer');
        $this->sendInternalCopies($sale, new SaleAgreementMail($sale, 'accepted', true));
    }

    /** Netcash Pay Now fields for the first payment, which also sets up the recurring debit. */
    public function startPayment(Sale $sale): array
    {
        $payment = Payment::create([
            'payable_type' => Sale::class,
            'payable_id' => $sale->id,
            'reference' => Payment::newReference(),
            'description' => Str::limit($sale->reference().' '.$sale->serviceLabel(), 50, ''),
            'amount' => $sale->initialPaymentAmount(),
            'status' => 'pending',
            'is_subscription' => true,
            'subscription_frequency' => $sale->netcashFrequency(),
            'subscription_start_date' => $sale->firstDebitDate()->toDateString(),
            'subscription_amount' => $sale->periodAmount(),
        ]);

        return [
            'm1' => config('netcash.service_key'),
            'm2' => config('netcash.vendor_key'),
            'p2' => $payment->reference,
            'p3' => $payment->description,
            'p4' => number_format((float) $payment->amount, 2, '.', ''),
            'Budget' => 'Y',
            'action_url' => config('netcash.pay_now_url'),
            // Recurring debit: tokenise the card and keep debiting at the agreed frequency
            // until cancelled (12-month term, then month-to-month per clause 5).
            'm14' => 1,
            'm16' => 1,
            'm17' => 999,
            'm18' => $sale->netcashFrequency(),
            'm19' => $sale->firstDebitDate()->format('Y-m-d'),
            'm20' => number_format($sale->periodAmount(), 2, '.', ''),
        ];
    }

    /**
     * First payment cleared (from Netcash's Notify postback). Creates or links the
     * customer's login, starts their plan(s) in the portal, converts the lead and
     * sends the welcome email. Safe to call more than once.
     */
    public function markPaid(Sale $sale, ?Payment $payment = null): void
    {
        if ($sale->isPaid()) {
            return;
        }

        [$customer, $isNewAccount] = DB::transaction(function () use ($sale) {
            [$customer, $isNewAccount] = $this->findOrCreateCustomer($sale);

            if ($customer) {
                $planIds = Plan::whereIn('key', Sale::SERVICE_PLAN_KEYS[$sale->service] ?? [])->pluck('id');

                foreach ($planIds as $planId) {
                    $customer->subscriptions()->firstOrCreate(
                        ['plan_id' => $planId, 'status' => 'active'],
                        ['started_at' => $sale->inception_date ?? now()]
                    );
                }
            }

            $sale->forceFill([
                'status' => 'paid',
                'paid_at' => now(),
                'user_id' => $customer?->id,
            ])->save();

            $sale->lead?->update(['status' => 'converted']);

            return [$customer, $isNewAccount];
        });

        if ($customer) {
            $this->deliver($customer->email, new CustomerWelcomeMail($sale, $customer, $isNewAccount ? $this->setPasswordUrl($customer) : null), $sale, 'welcome email');
        }

        $this->sendInternalCopies($sale, new SalePaidNotification($sale, $payment, $customer));
    }

    /** Signed link to set a password for a newly created customer account. Valid 7 days, dies once used. */
    public function setPasswordUrl(User $user): string
    {
        return URL::temporarySignedRoute('welcome.password', now()->addDays(7), [
            'user' => $user->id,
            'h' => $this->passwordFingerprint($user),
        ]);
    }

    /** Changes as soon as the password changes, which makes a used set-password link invalid. */
    public function passwordFingerprint(User $user): string
    {
        return substr(hash('sha256', $user->getAuthPassword().$user->id), 0, 20);
    }

    /**
     * @return array{0: ?User, 1: bool} the customer, and whether the account is new
     */
    private function findOrCreateCustomer(Sale $sale): array
    {
        $existing = User::whereRaw('LOWER(email) = ?', [strtolower($sale->email)])->first();

        if ($existing) {
            if (! $existing->isCustomer()) {
                // The email belongs to a sales rep: never turn a staff login into a customer.
                Log::warning('Sale paid but its email belongs to a non-customer login; no customer account linked.', [
                    'sale_id' => $sale->id,
                    'user_id' => $existing->id,
                ]);

                return [null, false];
            }

            return [$existing, false];
        }

        $contact = trim($sale->contact_person ?: $sale->customer_name);
        $parts = preg_split('/\s+/', $contact, 2);

        $user = User::create([
            'name' => $parts[0] ?? $contact,
            'surname' => $parts[1] ?? null,
            'email' => strtolower($sale->email),
            'phone' => $sale->phone,
            'password' => Hash::make(Str::random(40)),
            'role' => 'customer',
        ]);

        // They proved the address by accepting from the emailed link.
        $user->forceFill(['email_verified_at' => now()])->save();

        return [$user, true];
    }

    private function sendInternalCopies(Sale $sale, Mailable $mail): void
    {
        $sale->loadMissing('salesRep');

        $recipients = array_values(array_unique(array_filter([
            strtolower($this->adminAddress()),
            $sale->salesRep?->email ? strtolower($sale->salesRep->email) : null,
        ])));

        foreach ($recipients as $recipient) {
            $this->deliver($recipient, clone $mail, $sale, 'internal copy');
        }
    }

    /** Email failures never break the flow; they go to the log to be fixed, like the contact form. */
    private function deliver(string $to, Mailable $mail, Sale $sale, string $what): bool
    {
        try {
            Mail::to($to)->send($mail);

            return true;
        } catch (\Throwable $e) {
            Log::error('Sale email failed: '.$what, [
                'sale_id' => $sale->id,
                'to' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /** The logo embedded straight into the PDF, so it works wherever public/ happens to live on the server. */
    private function logoDataUri(): ?string
    {
        // dompdf needs the GD extension to draw PNGs; without it, leave the logo out rather than fail.
        if (! extension_loaded('gd')) {
            return null;
        }

        foreach ([public_path('images/logo.png'), base_path('../public_html/images/logo.png')] as $path) {
            if (is_file($path)) {
                return 'data:image/png;base64,'.base64_encode((string) file_get_contents($path));
            }
        }

        return null;
    }
}
