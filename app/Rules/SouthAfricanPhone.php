<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SouthAfricanPhone implements ValidationRule
{
    /**
     * Accepts South African numbers in these forms:
     *   0821234567
     *   +27821234567
     *   27821234567
     * Spaces and dashes are stripped before checking, so "082 123 4567"
     * and "082-123-4567" are both fine too.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $digitsOnly = preg_replace('/[\s\-]/', '', (string) $value);

        if (! preg_match('/^(\+?27|0)[1-9][0-9]{8}$/', $digitsOnly)) {
            $fail('Enter a valid South African phone number, e.g. 082 123 4567 or +27 82 123 4567.');
        }
    }
}
