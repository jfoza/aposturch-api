<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class AdminUsersValidationsService
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
