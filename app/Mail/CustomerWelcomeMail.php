<?php

namespace App\Mail;

use App\Models\Sale;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/** Sent once the first payment clears: the service is active, here's your portal login. */
class CustomerWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /** $setPasswordUrl is null when the customer already had a login. */
    public function __construct(public Sale $sale, public User $customer, public ?string $setPasswordUrl = null)
    {
    }

    public function build()
    {
        return $this->subject('Welcome to SP Pest Control: your service is active')
            ->view('emails.customer-welcome');
    }
}
