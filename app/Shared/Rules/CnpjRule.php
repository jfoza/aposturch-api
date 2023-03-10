<?php

namespace App\Shared\Rules;

use App\Shared\Helpers\ValidationDocsHelper;
use Illuminate\Contracts\Validation\Rule;

class CnpjRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return ValidationDocsHelper::validateCNPJ($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return ':attribute inválido.';
    }
}
