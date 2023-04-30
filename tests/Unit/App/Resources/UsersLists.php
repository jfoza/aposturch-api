<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class UsersLists
{
    public static function findAllUsers(): Collection
    {
        return Collection::make([
            [
                User::NAME     => "UserName",
                User::EMAIL    => "email.example@email.com",
                User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
                User::ACTIVE   => true,
                User::ID       => Uuid::uuid4()->toString(),
            ]
        ]);
    }

    public static function showUser(): object
    {
        return (object) ([
            User::NAME     => "UserName",
            User::EMAIL    => "email.example@email.com",
            User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE   => true,
            User::ID       => Uuid::uuid4()->toString(),
        ]);
    }

    public static function showUserChurch(string|null $churchId = null): object
    {
        if(is_null($churchId))
        {
            $churchId = Uuid::uuid4()->toString();
        }

        return (object) ([
            User::NAME     => "UserName",
            User::EMAIL    => "email.example@email.com",
            User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE   => true,
            User::ID       => Uuid::uuid4()->toString(),
            'church'       => collect([
                (object) ([
                    Church::ID   => $churchId,
                    Church::NAME => 'test',
                ])
            ]),
        ]);
    }
}
