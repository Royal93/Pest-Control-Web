<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'property_type', 'service_interest', 'message', 'source',
        'captured_by', 'status', 'address', 'suburb', 'province', 'pest_type',
        'referral_channel', 'notes', 'next_follow_up_date', 'consent',
    ];

    protected function casts(): array
    {
        return [
            'next_follow_up_date' => 'date',
            'consent' => 'boolean',
        ];
    }

    /** Every status a lead can be in, in the order they're expected to progress. */
    public const STATUSES = ['new', 'contacted', 'quoted', 'converted', 'not_interested'];

    /** Statuses that mean a lead is still being worked, grouped for the admin "In Progress" tab. */
    public const IN_PROGRESS_STATUSES = ['new', 'contacted', 'quoted'];

    /** The marketing/sales rep who captured this lead in the field, if any. */
    public function capturedBy()
    {
        return $this->belongsTo(User::class, 'captured_by');
    }

    public function documents()
    {
        return $this->hasMany(LeadDocument::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'New',
            'contacted' => 'Contacted',
            'quoted' => 'Quoted',
            'converted' => 'Converted',
            'not_interested' => 'Not interested',
            default => ucfirst($this->status),
        };
    }

    /** Tailwind classes for a small coloured status badge, kept in one place so every view matches. */
    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'new' => 'bg-gray-100 text-gray-700',
            'contacted' => 'bg-blue-100 text-blue-700',
            'quoted' => 'bg-amber-100 text-amber-700',
            'converted' => 'bg-green-100 text-green-700',
            'not_interested' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-700',
        };
    }

    /**
     * Whether this lead's email already matches a registered customer account.
     * Pass the set of customer emails in (lower-cased) to avoid a query per row
     * when checking a whole list of leads at once — see Admin\LeadController.
     */
    public function hasCustomerAccount(array $customerEmailsLower): bool
    {
        return $this->email && in_array(strtolower($this->email), $customerEmailsLower, true);
    }
}
