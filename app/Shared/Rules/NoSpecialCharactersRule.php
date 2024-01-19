<?php

namespace App\Shared\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NoSpecialCharactersRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(preg_match('/[!@#$%^&*(),.?":{}|<>]/', $value))
        {
            $fail("O campo {$attribute} não pode conter caracteres especiais.");
        }
    }
}
