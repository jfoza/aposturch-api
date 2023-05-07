<?php

namespace App\Shared\Rules;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class ManyUuidv4Rule implements ValidationRule
{
    /**
     * @throws AppException
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(!is_array($value))
        {
            throw new AppException(
                MessagesEnum::MUST_BE_AN_ARRAY->value,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        foreach ($value as $uuid) {
            if(!Uuid::isValid($uuid))
            {
                throw new AppException(
                    MessagesEnum::INVALID_UUID->value,
                    Response::HTTP_UNPROCESSABLE_ENTITY
                );
            }
        }
    }
}
