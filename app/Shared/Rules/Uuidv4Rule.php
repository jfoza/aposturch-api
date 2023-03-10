<?php

namespace App\Shared\Rules;

use Illuminate\Contracts\Validation\Rule;
use Ramsey\Uuid\Uuid;

class Uuidv4Rule implements Rule
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
        if(is_array($value)) {
            foreach ($value as $uuid) {
                if(is_null($uuid)) {
                    return false;
                }

                return Uuid::isValid($uuid);
            }
        }

        if(is_string($value) && !empty($value)) {
            return Uuid::isValid($value);
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
        return 'O valor informado não é um UUID válido.';
    }
}
