<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    // Only ever store a masked last-4 and a payment-gateway token.
    // Never add a raw card number column here.
    protected $fillable = ['user_id', 'cardholder_name', 'last4', 'exp_month', 'exp_year', 'gateway_token'];

    protected $hidden = ['gateway_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
