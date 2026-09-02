<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payment extends Model
{
    protected $fillable = [
        'payable_type', 'payable_id',
        'reference', 'description', 'amount', 'status',
        'method', 'request_trace', 'decline_reason',
        'cc_token', 'cc_holder', 'cc_masked', 'cc_expiry',
        'is_subscription', 'subscription_frequency',
        'subscription_start_date', 'subscription_amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'subscription_amount' => 'decimal:2',
        'subscription_start_date' => 'date',
        'is_subscription' => 'boolean',
    ];

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Generate a reference that is unique, short enough for Netcash's p2
     * field (max 25 chars), and traceable back to us in the Netcash statement.
     */
    public static function newReference(): string
    {
        return 'SP-' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -8));
    }
}
