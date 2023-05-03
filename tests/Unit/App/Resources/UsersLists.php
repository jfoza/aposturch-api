<?php

namespace Tests\Unit\App\Resources;

use App\Features\Module\Modules\Infra\Models\Module;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Enums\ModulesEnum;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Utils\Hash;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection as SupportCollection;
use Ramsey\Uuid\Uuid;

class UsersLists
{
    public static function findAllUsers(): SupportCollection
    {
        return SupportCollection::make([
            [
                User::NAME     => "UserName",
                User::EMAIL    => "email.example@email.com",
                User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
                User::ACTIVE   => true,
                User::ID       => Uuid::uuid4()->toString(),
            ]
        ]);
    }

    public static function showUser(?string $id = null): object
    {
        if(is_null($id))
        {
            $id = Uuid::uuid4()->toString();
        }

        return (object) ([
            User::NAME     => "UserName",
            User::EMAIL    => "email.example@email.com",
            User::PASSWORD => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE   => true,
            User::ID       => $id,
        ]);
    }

    public static function getAdminUserInAuth(string $pass = null, bool $active = true): object
    {
        if(is_null($pass))
        {
            $pass = RandomStringHelper::alnumGenerate();
        }

        $userId = Uuid::uuid4()->toString();

        return (object) ([
            User::NAME     => "UserName",
            User::EMAIL    => "email.example@email.com",
            User::AVATAR   => null,
            User::PASSWORD => Hash::generateHash($pass),
            User::ACTIVE   => $active,
            User::ID       => $userId,
            'adminUser'    => (object) ([
                AdminUser::ID => Uuid::uuid4()->toString(),
                AdminUser::USER_ID => $userId
            ]),
            'profile' => DatabaseCollection::make([
                (object) ([
                    Profile::ID => Uuid::uuid4()->toString(),
                    Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER->value
                ]),
            ]),
            'module' => DatabaseCollection::make([
                (object) ([
                    Module::ID => Uuid::uuid4()->toString(),
                    Module::MODULE_UNIQUE_NAME => ModulesEnum::USERS->value,
                ])
            ]),
        ]);
    }
}
