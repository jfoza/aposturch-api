<?php

namespace App\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ZipCodeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!is_numeric($value)) {
            $fail("O campo {$attribute} é inválido.");
        }

        if(strlen($value) < 8 || strlen($value) > 10) {
            $fail("O campo {$attribute} é inválido.");
        }
    }
}
