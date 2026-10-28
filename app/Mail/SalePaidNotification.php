<?php

namespace App\Mail;

use App\Models\Payment;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Internal: tells admin and the rep that a sale's first payment has cleared. */
class SalePaidNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Sale $sale, public ?Payment $payment = null, public ?User $customer = null)
    {
    }

    public function build()
    {
        return $this->subject("Payment received: {$this->sale->reference()} {$this->sale->customer_name}")
            ->view('emails.sale-paid');
    }
}
