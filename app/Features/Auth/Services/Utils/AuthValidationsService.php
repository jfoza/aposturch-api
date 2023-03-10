<?php

namespace App\Features\Auth\Services\Utils;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use App\Features\Users\Users\Services\Utils\HashService;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class AuthValidationsService
{
    /**
     * @throws AppException
     */
    public static function userExistsLogin(mixed $user)
    {
        if(empty($user)) {
            throw new AppException(
                MessagesEnum::LOGIN_ERROR,
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $user->user;
    }

    /**
     * @throws AppException
     */
    public static function userExistsForgotPassword(mixed $user)
    {
        if(empty($user)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $user->user;
    }

    /**
     * @throws AppException
     */
    public static function passwordVerify(string $payload, string $hashed): void
    {
        if(!HashService::compareHash($payload, $hashed)) {
            throw new AppException(
                MessagesEnum::LOGIN_ERROR,
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function isActive(bool $active): void
    {
        if(!$active) {
            throw new AppException(
                MessagesEnum::INACTIVE_USER,
                Response::HTTP_UNAUTHORIZED
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function forgotPasswordExists(mixed $forgotPassword): void
    {
        if(empty($forgotPassword)) {
            throw new AppException(MessagesEnum::PASSWORD_CODE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function isValidForgotPassword(Carbon|string $currentDate, Carbon|string $validate, bool $active): void
    {
        if(!$currentDate->lt($validate) || !$active) {
            throw new AppException(
                MessagesEnum::INVALID_FORGOT_PASSWORD_CODE,
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    /**
     * @throws AppException
     */
    public static function validateIfUserHasAlreadyVerifiedEmail(bool $verifiedEmail, bool $login = true): void
    {
        if(!$verifiedEmail) {
            throw new AppException(
                MessagesEnum::UNVERIFIED_EMAIL,
                $login ? Response::HTTP_UNAUTHORIZED : Response::HTTP_BAD_REQUEST
            );
        }
    }

    public static function userHasModules(mixed $user)
    {
        if(!$modules = $user->module)
        {

        }
    }
}
