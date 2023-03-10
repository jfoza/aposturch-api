<?php

namespace App\Features\Users\Users\Services\Utils;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use Symfony\Component\HttpFoundation\Response;

class UsersValidationsService
{
    /**
     * @throws AppException
     */
    public static function validateUserExistsById(
        string $userId,
        UsersRepositoryInterface $usersRepository
    )
    {
        if(!$user = $usersRepository->findById($userId))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $user;
    }

    /**
     * @throws AppException
     */
    public static function checkIfPasswordsMatch(
        string $payload,
        string $hashed
    ): void
    {
        if(!HashService::compareHash($payload, $hashed))
        {
            throw new AppException(
                MessagesEnum::INVALID_CURRENT_PASSWORD,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExists(
        UsersRepositoryInterface $usersRepository,
        string $email
    ): void
    {
        if(!empty($usersRepository->findByEmail($email))) {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExistsUpdate(
        UsersRepositoryInterface $usersRepository,
        string $id,
        string $email
    )
    {
        $user = $usersRepository->findByEmail($email);

        if($user && $user->id != $id) {
            throw new AppException(
                MessagesEnum::EMAIL_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }

        return $user;
    }

    /**
     * @throws AppException
     */
    public static function returnProfileExists(
        ProfilesRepositoryInterface $profilesRepository,
        string $profileId,
    )
    {
        $profile = $profilesRepository->findById($profileId);

        if(empty($profile)) {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $profile;
    }

    /**
     * @throws AppException
     */
    public static function isActiveUser(bool $active): void
    {
        if(!$active)
        {
            throw new AppException(
                MessagesEnum::INACTIVE_USER,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
