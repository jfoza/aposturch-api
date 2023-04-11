<?php

namespace App\Shared\Rules;

use App\Shared\Helpers\ValidationDocsHelper;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(ValidationDocsHelper::validateCNPJ($value))
        {
            $fail("O campo {$attribute} é inválido.");
        }
    }
}
