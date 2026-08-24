<?php

namespace App\Livewire;

use App\Rules\LuhnCardNumber;
use Livewire\Component;

class PaymentMethodForm extends Component
{
    public string $cardholderName = '';
    public string $cardNumber = '';
    public string $expMonth = '';
    public string $expYear = '';
    public string $cvv = '';
    public bool $showForm = false;

    public function save()
    {
        $this->validate([
            'cardholderName' => 'required|string|max:255',
            'cardNumber' => ['required', 'string', new LuhnCardNumber],
            'expMonth' => 'required|integer|min:1|max:12',
            'expYear' => 'required|integer|min:'.date('y').'|max:'.(date('y') + 20),
            'cvv' => 'required|digits_between:3,4',
        ]);

        // Confirm the expiry date hasn't already passed (month + year together,
        // not just checked separately - e.g. "03/26" needs rejecting once it's
        // April 2026, not just once the year rolls over).
        $expiry = \Carbon\Carbon::createFromDate(2000 + (int) $this->expYear, (int) $this->expMonth, 1)->endOfMonth();
        if ($expiry->isPast()) {
            $this->addError('expMonth', 'This card has expired.');
            return;
        }

        // In production: send cardNumber and cvv straight to your payment
        // gateway's hosted card field (PayFast Onsite, Stripe Elements, etc.)
        // from the browser, and only store the token/last4 they return.
        // Never let a raw card number or CVV reach this method in a real
        // deployment - the CVV specifically must never be stored anywhere,
        // even temporarily, which is why it's not in $fillable below.
        $last4 = substr(preg_replace('/\D/', '', $this->cardNumber), -4);

        auth()->user()->paymentMethods()->updateOrCreate([], [
            'cardholder_name' => $this->cardholderName,
            'last4' => $last4,
            'exp_month' => $this->expMonth,
            'exp_year' => $this->expYear,
        ]);

        $this->reset(['cardNumber', 'cvv']);
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.payment-method-form', [
            'existing' => auth()->user()->paymentMethods()->latest()->first(),
        ]);
    }
}
