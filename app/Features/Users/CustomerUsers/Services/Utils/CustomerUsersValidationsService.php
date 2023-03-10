<?php

namespace App\Features\Users\CustomerUsers\Services\Utils;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class CustomerUsersValidationsService
{
    /**
     * @throws AppException
     */
    public static function customerUserIdExists(
        CustomerUsersRepositoryInterface $customerUsersRepository,
        string $userId
    )
    {
        $customerUser = $customerUsersRepository->findByUserId($userId);

        if(empty($customerUser) || empty($customerUser->user->person)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $customerUser;
    }

    /**
     * @throws AppException
     */
    public static function isEmailAlreadyVerify(bool $emailVerify): void
    {
        if ($emailVerify)
        {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_VERIFIED,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
