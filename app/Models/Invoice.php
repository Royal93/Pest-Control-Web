<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Invoice extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'invoice_number',
        'amount',
        'status',
        'description',
        'file_path',
        'issued_at',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /** True when an admin uploaded a file for this invoice. */
    public function hasFile(): bool
    {
        return filled($this->file_path);
    }

    /** The number shown to people: the admin's own number if given, otherwise the record id. */
    public function displayNumber(): string
    {
        return $this->invoice_number ?: str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    /** A safe file name for downloads, built from the invoice number and the stored file type. */
    public function downloadName(): string
    {
        $base = Str::slug($this->invoice_number ?: '') ?: 'invoice-'.$this->id;
        $extension = strtolower(pathinfo((string) $this->file_path, PATHINFO_EXTENSION)) ?: 'pdf';

        return $base.'.'.$extension;
    }
}
