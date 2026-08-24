<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class LuhnCardNumber implements ValidationRule
{
    /**
     * Validates a card number's format and checksum (the Luhn algorithm -
     * the same check every real card issuer uses to catch typos). This does
     * NOT confirm the card is real, active, or has funds - only that the
     * number is well-formed. Actual verification happens at the payment
     * gateway when the real integration goes in.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digits = preg_replace('/\s+/', '', (string) $value);

        if (! preg_match('/^\d{13,19}$/', $digits)) {
            $fail('Enter a valid card number (13-19 digits).');
            return;
        }

        $sum = 0;
        $alternate = false;

        for ($i = strlen($digits) - 1; $i >= 0; $i--) {
            $n = (int) $digits[$i];
            if ($alternate) {
                $n *= 2;
                if ($n > 9) {
                    $n -= 9;
                }
            }
            $sum += $n;
            $alternate = ! $alternate;
        }

        if ($sum % 10 !== 0) {
            $fail('That card number doesn\'t look right - please double-check it.');
        }
    }
}
