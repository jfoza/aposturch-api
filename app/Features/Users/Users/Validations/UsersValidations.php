<?php

namespace App\Features\Users\Users\Validations;

use App\Exceptions\AppException;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Hash;
use Symfony\Component\HttpFoundation\Response;

class UsersValidations
{
    /**
     * @throws AppException
     */
    public static function validateUserExistsById(
        string $userId,
        UsersRepositoryInterface $usersRepository
    ): object
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
        if(!Hash::compareHash($payload, $hashed))
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
    public static function phoneAlreadyExists(
        UsersRepositoryInterface $usersRepository,
        string $phone
    ): void
    {
        if(!empty($usersRepository->findByPhone($phone))) {
            throw new AppException(
                MessagesEnum::PHONE_ALREADY_EXISTS,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function emailAlreadyExistsUpdateException(): void
    {
        throw new AppException(
            MessagesEnum::EMAIL_ALREADY_EXISTS,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @throws AppException
     */
    public static function phoneAlreadyExistsUpdateException(): void
    {
        throw new AppException(
            MessagesEnum::PHONE_ALREADY_EXISTS,
            Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @throws AppException
     */
    public static function returnProfileExists(
        ProfilesRepositoryInterface $profilesRepository,
        string $profileId,
    )
    {
        if(!$profile = $profilesRepository->findById($profileId)) {
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
        if (!$active) {
            throw new AppException(
                MessagesEnum::INACTIVE_USER,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function userHasImage(mixed $user): void
    {
        if(empty($user->image))
        {
            throw new AppException(
                MessagesEnum::USER_WITHOUT_IMAGE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
