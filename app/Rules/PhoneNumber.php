<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneNumber implements ValidationRule
{
    public const PATTERN = '/^\+?[0-9]{9,11}$/';

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value) || ! preg_match(self::PATTERN, $value)) {
            $fail('Numer telefonu musi zawierać od 9 do 11 cyfr i może zaczynać się od +.');
        }
    }
}
