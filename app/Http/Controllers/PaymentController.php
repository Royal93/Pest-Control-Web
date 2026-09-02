<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Start a once-off payment (e.g. a plan's initial fee, or a manual
     * invoice). Creates a pending Payment row, then shows a page that
     * auto-submits the Netcash form — Netcash requires a real browser
     * form POST to a parent window, not an API/AJAX call.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:50',
            'amount'      => 'required|numeric|min:5',
            // Optional: link this payment to any model (a Plan, an Order, etc)
            'payable_type' => 'nullable|string',
            'payable_id'   => 'nullable|integer',
        ]);

        $payment = Payment::create([
            'payable_type' => $validated['payable_type'] ?? null,
            'payable_id'   => $validated['payable_id'] ?? null,
            'reference'    => Payment::newReference(),
            'description'  => $validated['description'],
            'amount'       => $validated['amount'],
            'status'       => 'pending',
        ]);

        return view('payments.redirect', [
            'netcash' => $this->buildFields($payment),
        ]);
    }

    /**
     * Start a recurring subscription (RatGuard / RoachGuard / AntArmor).
     * frequency: 1 Monthly, 2 Weekly, 3 Bi-weekly, 4 Quarterly, 5 Six-monthly, 6 Annually, 7 Daily
     */
    public function checkoutSubscription(Request $request)
    {
        $validated = $request->validate([
            'description'  => 'required|string|max:50',
            'amount'       => 'required|numeric|min:5',   // first payment (can be R0.01 for a trial, see notes below)
            'recurring_amount' => 'required|numeric|min:5',
            'frequency'    => 'required|integer|min:1|max:7',
            'cycles'       => 'required|integer|min:1|max:999',
            'start_date'   => 'required|date',
            'payable_type' => 'nullable|string',
            'payable_id'   => 'nullable|integer',
        ]);

        $payment = Payment::create([
            'payable_type' => $validated['payable_type'] ?? null,
            'payable_id'   => $validated['payable_id'] ?? null,
            'reference'    => Payment::newReference(),
            'description'  => $validated['description'],
            'amount'       => $validated['amount'],
            'status'       => 'pending',
            'is_subscription' => true,
            'subscription_frequency'  => $validated['frequency'],
            'subscription_start_date' => $validated['start_date'],
            'subscription_amount'     => $validated['recurring_amount'],
        ]);

        $fields = $this->buildFields($payment);
        $fields['m14'] = 1; // must tokenise the card for a subscription
        $fields['m16'] = 1; // subscription indicator
        $fields['m17'] = $validated['cycles'];
        $fields['m18'] = $validated['frequency'];
        $fields['m19'] = \Carbon\Carbon::parse($validated['start_date'])->format('Y-m-d');
        $fields['m20'] = number_format($validated['recurring_amount'], 2, '.', '');

        return view('payments.redirect', ['netcash' => $fields]);
    }

    /**
     * Notify URL — server-to-server, called for EVERY transaction regardless
     * of whether the customer's browser makes it back to our site. This is
     * the only postback that should be trusted to actually mark something
     * paid; Accept/Decline are for the customer's screen only.
     */
    public function notify(Request $request)
    {
        $payment = Payment::where('reference', $request->input('Reference'))->first();

        if (! $payment) {
            Log::warning('Netcash notify: unknown reference', $request->all());
            return response('', 200); // still 200 — Netcash retries on failure, don't want infinite retries on a bad reference
        }

        $accepted = filter_var($request->input('TransactionAccepted'), FILTER_VALIDATE_BOOLEAN);

        $payment->update([
            'status'         => $accepted ? 'accepted' : 'declined',
            'method'         => $request->input('Method'),
            'request_trace'  => $request->input('RequestTrace'),
            'decline_reason' => $request->input('Reason'),
            'cc_token'       => $request->input('ccToken'),
            'cc_holder'      => $request->input('ccHolder'),
            'cc_masked'      => $request->input('ccMasked'),
            'cc_expiry'      => $request->input('ccExpiry'),
        ]);

        // TODO once payable models exist: if ($accepted && $payment->payable) {
        //     $payment->payable->markPaid($payment);
        // }

        return response('', 200);
    }

    /**
     * Accept URL — customer's browser lands here after a successful
     * real-time payment (card / Instant EFT). Do not rely on this for
     * fulfilment, only for what the customer sees.
     */
    public function accept(Request $request)
    {
        $payment = Payment::where('reference', $request->input('Reference'))->first();

        return view('payments.accept', ['payment' => $payment]);
    }

    /**
     * Decline URL — customer's browser lands here after a failed real-time
     * payment.
     */
    public function decline(Request $request)
    {
        $payment = Payment::where('reference', $request->input('Reference'))->first();

        return view('payments.decline', [
            'payment' => $payment,
            'reason'  => $request->input('Reason'),
        ]);
    }

    /**
     * Redirect URL — used for delayed methods (Bank EFT, Retail) where the
     * customer clicks "Complete" on Netcash's own screen but hasn't actually
     * paid yet, and for the Cancel button.
     */
    public function redirect(Request $request)
    {
        $payment = Payment::where('reference', $request->input('Reference'))->first();

        return view('payments.pending', ['payment' => $payment]);
    }

    /**
     * Shared field set for both one-off and subscription payments.
     */
    private function buildFields(Payment $payment): array
    {
        return [
            'm1'      => config('netcash.service_key'),
            'm2'      => config('netcash.vendor_key'),
            'p2'      => $payment->reference,
            'p3'      => $payment->description,
            'p4'      => number_format($payment->amount, 2, '.', ''),
            'Budget'  => 'Y',
            'action_url' => config('netcash.pay_now_url'),
        ];
    }
}
