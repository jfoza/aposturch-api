<?php

namespace App\Shared\Rules;

use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\StatesEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StatesRule implements ValidationRule
{
    /**
     * @inheritDoc
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $states = array_column(StatesEnum::cases(), 'value');

        if(!in_array($value, $states))
        {
            $fail(MessagesEnum::INVALID_UF->value);
        }
    }
}
