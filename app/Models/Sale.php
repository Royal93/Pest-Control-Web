<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * A completed sale: the questionnaire a rep (or admin) fills in, which becomes
 * the customer's personalised service agreement. Lifecycle:
 *   sent      agreement emailed, waiting for the customer to accept
 *   accepted  customer accepted online, waiting for the first payment
 *   paid      first payment cleared: customer account + subscription active
 *   cancelled withdrawn by admin before payment
 */
class Sale extends Model
{
    protected $fillable = [
        'lead_id', 'sales_rep_id', 'captured_by_admin', 'user_id',
        'customer_name', 'contact_person', 'phone', 'email', 'service_address', 'suburb', 'property_type',
        'service', 'children_or_pets', 'bait_stations', 'treatment_areas',
        'agreement_date', 'inception_date', 'activation_fee', 'monthly_subscription', 'payment_frequency',
        'status', 'token', 'agreement_path', 'sent_at',
        'accepted_at', 'accepted_name', 'accepted_ip', 'accepted_user_agent',
        'paid_at', 'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'children_or_pets' => 'boolean',
            'bait_stations' => 'integer',
            'agreement_date' => 'date',
            'inception_date' => 'date',
            'activation_fee' => 'decimal:2',
            'monthly_subscription' => 'decimal:2',
            'sent_at' => 'datetime',
            'accepted_at' => 'datetime',
            'paid_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public const PROPERTY_TYPES = [
        'house' => 'House',
        'flat' => 'Flat / Apartment',
        'townhouse' => 'Townhouse',
        'business' => 'Business premises',
    ];

    public const SERVICES = [
        'cockroach' => 'Cockroach control',
        'rodent' => 'Rodent control',
        'both' => 'Both',
    ];

    /** Plan keys each service maps to, for pricing defaults and the customer's portal subscription. */
    public const SERVICE_PLAN_KEYS = [
        'cockroach' => ['roachguard-360'],
        'rodent' => ['ratguard-monthly'],
        'both' => ['roachguard-360', 'ratguard-monthly'],
    ];

    /** label, months per billing period, Netcash Pay Now m18 frequency code */
    public const FREQUENCIES = [
        'monthly' => ['label' => 'Monthly', 'months' => 1, 'netcash' => 1],
        'quarterly' => ['label' => 'Quarterly', 'months' => 3, 'netcash' => 4],
        'biannual' => ['label' => 'Biannual', 'months' => 6, 'netcash' => 5],
        'annual' => ['label' => 'Annual', 'months' => 12, 'netcash' => 6],
    ];

    public const STATUSES = [
        'sent' => 'Awaiting acceptance',
        'accepted' => 'Awaiting payment',
        'paid' => 'Active',
        'cancelled' => 'Cancelled',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function salesRep()
    {
        return $this->belongsTo(User::class, 'sales_rep_id');
    }

    public function capturedByAdmin()
    {
        return $this->belongsTo(AdminUser::class, 'captured_by_admin');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payments()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    // ── Labels ─────────────────────────────────────────────────────

    public function reference(): string
    {
        return 'SP-AG-'.str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function serviceLabel(): string
    {
        return self::SERVICES[$this->service] ?? ucfirst((string) $this->service);
    }

    public function propertyLabel(): string
    {
        return self::PROPERTY_TYPES[$this->property_type] ?? ucfirst((string) $this->property_type);
    }

    public function frequencyLabel(): string
    {
        return self::FREQUENCIES[$this->payment_frequency]['label'] ?? ucfirst((string) $this->payment_frequency);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'sent' => 'bg-amber-100 text-amber-700',
            'accepted' => 'bg-blue-100 text-blue-700',
            'paid' => 'bg-green-100 text-green-700',
            'cancelled' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    public function salesRepName(): ?string
    {
        return $this->salesRep ? $this->salesRep->fullName() : null;
    }

    public function includesRodent(): bool
    {
        return in_array($this->service, ['rodent', 'both'], true);
    }

    public function includesCockroach(): bool
    {
        return in_array($this->service, ['cockroach', 'both'], true);
    }

    // ── Money and dates ────────────────────────────────────────────

    public function monthsPerPeriod(): int
    {
        return self::FREQUENCIES[$this->payment_frequency]['months'] ?? 1;
    }

    /** What each recurring debit is: the monthly subscription times the months in a billing period. */
    public function periodAmount(): float
    {
        return round((float) $this->monthly_subscription * $this->monthsPerPeriod(), 2);
    }

    /** The first payment, made through the secure payment link: activation fee plus the first period. */
    public function initialPaymentAmount(): float
    {
        return round((float) $this->activation_fee + $this->periodAmount(), 2);
    }

    /** Recurring debits start one billing period after inception (the first period is in the initial payment). */
    public function firstDebitDate(): Carbon
    {
        $start = $this->inception_date && $this->inception_date->isFuture()
            ? $this->inception_date->copy()
            : now()->startOfDay();

        return $start->addMonthsNoOverflow($this->monthsPerPeriod());
    }

    public function netcashFrequency(): int
    {
        return self::FREQUENCIES[$this->payment_frequency]['netcash'] ?? 1;
    }

    // ── State ──────────────────────────────────────────────────────

    public function isAwaitingAcceptance(): bool
    {
        return $this->status === 'sent';
    }

    public function isAwaitingPayment(): bool
    {
        return $this->status === 'accepted';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /** The customer's private link to view and accept the agreement. */
    public function agreementUrl(): string
    {
        return route('agreements.show', $this->token);
    }

    public function paymentUrl(): string
    {
        return route('agreements.pay', $this->token);
    }

    public function pdfFileName(): string
    {
        return $this->reference().'-Service-Agreement.pdf';
    }
}
