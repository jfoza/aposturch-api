<?php

namespace App\Shared\Rules;

use Illuminate\Contracts\Validation\Rule;

class CodeVerificationEmailRule implements Rule
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
        if(!is_numeric($value)) {
            return false;
        }

        if(strlen($value) != 6) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Invalid :attribute.';
    }
}
