<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** A sales rep's request to be given an unassigned lead. Admin approves one per lead. */
class LeadClaim extends Model
{
    protected $fillable = ['lead_id', 'user_id', 'note', 'status', 'decided_at', 'decided_by_admin'];

    protected function casts(): array
    {
        return ['decided_at' => 'datetime'];
    }

    public const STATUSES = [
        'pending' => 'Waiting for approval',
        'approved' => 'Approved',
        'declined' => 'Not approved',
        'withdrawn' => 'Withdrawn',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function rep()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function decidedBy()
    {
        return $this->belongsTo(AdminUser::class, 'decided_by_admin');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst((string) $this->status);
    }

    public function statusBadgeClasses(): string
    {
        return match ($this->status) {
            'pending' => 'bg-amber-100 text-amber-700',
            'approved' => 'bg-green-100 text-green-700',
            'declined' => 'bg-red-100 text-red-700',
            default => 'bg-gray-100 text-gray-600',
        };
    }
}
