<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'surname', 'email', 'phone', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function subscriptions()
    {
        return $this->hasMany(\App\Models\Subscription::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(\App\Models\PaymentMethod::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class);
    }

    /** Leads this rep captured themselves (older leads, before only admin could capture). */
    public function capturedLeads()
    {
        return $this->hasMany(\App\Models\Lead::class, 'captured_by');
    }

    /** Leads admin has assigned to this sales rep. */
    public function assignedLeads()
    {
        return $this->hasMany(\App\Models\Lead::class, 'assigned_to');
    }

    /** Sales this rep has captured (service agreements sent to customers). */
    public function repSales()
    {
        return $this->hasMany(\App\Models\Sale::class, 'sales_rep_id');
    }

    /** Service agreements that belong to this customer. */
    public function sales()
    {
        return $this->hasMany(\App\Models\Sale::class, 'user_id');
    }

    /**
     * Real, signed-up customers only. Sales reps share this table (role =
     * marketing), and an admin may also have a customer login for testing,
     * so both are kept out of anything that lists or counts customers.
     */
    public function scopeCustomers(Builder $query): Builder
    {
        $adminEmails = AdminUser::query()->pluck('email')
            ->map(fn ($email) => strtolower($email))
            ->all();

        return $query
            ->where(fn ($q) => $q->where('role', 'customer')->orWhereNull('role'))
            ->when($adminEmails, fn ($q) => $q->whereRaw(
                'LOWER(email) NOT IN ('.implode(',', array_fill(0, count($adminEmails), '?')).')',
                $adminEmails
            ));
    }

    /** Active sales reps (stored with role = marketing). */
    public function scopeSalesReps(Builder $query): Builder
    {
        return $query->where('role', 'marketing');
    }

    public function fullName(): string
    {
        return trim($this->name.' '.$this->surname);
    }

    public function isMarketing(): bool
    {
        return $this->role === 'marketing';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer' || $this->role === null;
    }
}
