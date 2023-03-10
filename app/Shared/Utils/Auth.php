<?php

namespace App\Shared\Utils;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Illuminate\Contracts\Auth\Authenticatable;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class Auth
{
    public static function generateAccessToken(string $id): mixed
    {
        return auth()->tokenById($id);
    }

    public static function getExpiresIn(): string
    {
        return env('JWT_TTL').' min';
    }

    public static function getTokenType(): string
    {
        return 'bearer';
    }

    public static function logout(): void
    {
        auth()->logout();
    }

    public static function getUser(): ?Authenticatable
    {
        return auth()->user();
    }

    /**
     * @throws AppException
     */
    public static function getCustomerUserId()
    {
        if(!$customerUser = auth()->user()->customerUser)
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $customerUser;
    }

    /**
     * @throws UserNotDefinedException
     */
    public static function authenticate(): Authenticatable
    {
        if (!$user = auth()->user()) {
            throw new UserNotDefinedException;
        }

        return $user;
    }

    public static function getId(): int|string|null
    {
        return auth()->id();
    }
}
