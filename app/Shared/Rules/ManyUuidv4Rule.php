<?php

namespace App\Shared\Rules;

use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ramsey\Uuid\Uuid;

class ManyUuidv4Rule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!is_array($value))
        {
            $fail(MessagesEnum::MUST_BE_AN_ARRAY->value);
        }

        foreach ($value as $uuid) {
            if(!Uuid::isValid($uuid))
            {
                $fail(MessagesEnum::INVALID_UUID->value);
            }
        }
    }
}
