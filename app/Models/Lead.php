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

    /** The marketing/sales rep who captured this lead in the field, if any. */
    public function capturedBy()
    {
        return $this->belongsTo(User::class, 'captured_by');
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
}
