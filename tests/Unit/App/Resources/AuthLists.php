<?php

namespace Tests\Unit\App\Resources;

use App\Domain\Helpers\Helpers;
use Carbon\Carbon;
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
                "avatar",
                "fullName",
                'role',
                'status',
                'ability'
            ]
        ];
    }

    public static function customerAuthStructure(): array
    {
        return [
            'accessToken',
            'tokenType',
            'expiresIn',
            'user' => [
                "id",
                "email",
                "avatar",
                "fullName",
                'role',
                'status',
                'phone',
                'zipCode',
                'address',
                'numberAddress',
                'complement',
                'district',
                'city'
            ]
        ];
    }

    public static function getForgotPassword(string|Carbon $date = null, bool $active = true): object
    {
        $userId = Uuid::uuid4()->toString();

        if(is_null($date)) {
            $currentDate = Helpers::getCurrentTimestampCarbon();

            $date = $currentDate->addDay()->format('Y-m-d H:i:s');
        }

        return (object) ([
            'id'       => Uuid::uuid4()->toString(),
            'user_id'  => $userId,
            'code'     => Uuid::uuid4()->toString(),
            'validate' => $date,
            'active'   => $active,

            'user' => (object) ([
              'id' => $userId,
            ])
        ]);
    }
}
