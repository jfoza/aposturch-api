<?php

namespace App\Features\Users\AdminUsers\Validations;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersValidations
{
    /**
     * @throws AppException
     */
    public static function adminUserNotFoundException(): void
    {
        throw new AppException(
            MessagesEnum::USER_NOT_FOUND,
            Response::HTTP_NOT_FOUND
        );
    }
}
