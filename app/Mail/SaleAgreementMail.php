<?php

namespace App\Mail;

use App\Models\Sale;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

/**
 * The service agreement email.
 *   stage 'sent':     "please review and accept" (with the private link)
 *   stage 'accepted': "thank you, here is your accepted agreement and payment link"
 *   internal = true:  the copy that goes to admin and the sales rep
 */
class SaleAgreementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Sale $sale, public string $stage = 'sent', public bool $internal = false)
    {
    }

    public function build()
    {
        $ref = $this->sale->reference();

        $subject = match (true) {
            $this->internal && $this->stage === 'accepted' => "Agreement accepted: {$ref} {$this->sale->customer_name}",
            $this->internal => "Agreement sent: {$ref} {$this->sale->customer_name}",
            $this->stage === 'accepted' => "Your SP Pest Control agreement {$ref}: next step, secure payment",
            default => "Your SP Pest Control service agreement {$ref}",
        };

        $mail = $this->subject($subject)->view('emails.sale-agreement');

        if ($this->sale->agreement_path && Storage::disk('local')->exists($this->sale->agreement_path)) {
            $mail->attachFromStorageDisk('local', $this->sale->agreement_path, $this->sale->pdfFileName(), [
                'mime' => 'application/pdf',
            ]);
        }

        return $mail;
    }
}
