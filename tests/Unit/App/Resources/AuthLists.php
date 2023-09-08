<?php

namespace Tests\Unit\App\Resources;

use App\Features\Auth\Responses\AuthUserResponse;
use App\Shared\Helpers\Helpers;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class AuthLists
{
    public static function accessToken(): string
    {
        return hash('sha256', 'testHash');
    }

    public static function getTTL(): string
    {
        return env('JWT_TTL').' min';
    }

    public static function tokenType(): string
    {
        return 'bearer';
    }

    public static function authStructure(): array
    {
        return [
            'accessToken',
            'tokenType',
            'expiresIn',
            'user' => [
                "id",
                "email",
                "avatarId",
                "fullName",
                'role',
                'status',
                'ability'
            ]
        ];
    }

    public static function getAuthUserResponse(): AuthUserResponse
    {
        $authUserResponse = new AuthUserResponse();

        $authUserResponse->id = Uuid::uuid4()->toString();
        $authUserResponse->email = 'email@email.com';
        $authUserResponse->avatarId = null;
        $authUserResponse->fullName = 'Test';
        $authUserResponse->role = Collection::make([]);
        $authUserResponse->status = true;
        $authUserResponse->ability = [];

        return $authUserResponse;
    }
}
