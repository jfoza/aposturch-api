<?php

namespace App\Shared\Rules;

use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ramsey\Uuid\Uuid;

class Uuid4Rule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!Uuid::isValid($value))
        {
            $fail(MessagesEnum::INVALID_UUID->value);
        }
    }
}
