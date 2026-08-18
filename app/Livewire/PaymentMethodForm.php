<?php

namespace App\Livewire;

use Livewire\Component;

class PaymentMethodForm extends Component
{
    public string $cardholderName = '';
    public string $cardNumber = '';
    public string $expMonth = '';
    public string $expYear = '';
    public bool $showForm = false;

    public function save()
    {
        $this->validate([
            'cardholderName' => 'required|string|max:255',
            'cardNumber' => 'required|string|min:12',
            'expMonth' => 'required|numeric|min:1|max:12',
            'expYear' => 'required|numeric|min:' . date('y'),
        ]);

        // In production: send $this->cardNumber to your payment gateway (Stripe/PayFast)
        // directly from the browser via their JS SDK, and only store the token they
        // return. Never let a raw card number reach this method in a real deployment.
        $last4 = substr(preg_replace('/\D/', '', $this->cardNumber), -4);

        auth()->user()->paymentMethods()->updateOrCreate([], [
            'cardholder_name' => $this->cardholderName,
            'last4' => $last4,
            'exp_month' => $this->expMonth,
            'exp_year' => $this->expYear,
        ]);

        $this->reset(['cardNumber']);
        $this->showForm = false;
    }

    public function render()
    {
        return view('livewire.payment-method-form', [
            'existing' => auth()->user()->paymentMethods()->latest()->first(),
        ]);
    }
}
