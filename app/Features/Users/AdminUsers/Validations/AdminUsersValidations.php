<?php

namespace App\Features\Users\AdminUsers\Validations;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersValidations
{
    /**
     * @throws AppException
     */
    public static function adminUserIdExists(
        AdminUsersRepositoryInterface $adminUsersRepository,
        string $userId
    ): void
    {
        if(empty($adminUsersRepository->findByUserId($userId))) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
