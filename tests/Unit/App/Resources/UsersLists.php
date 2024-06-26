<?php

namespace Tests\Unit\App\Resources;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Features\Module\Modules\Models\Module;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Enums\ModulesUniqueNameEnum;
use App\Shared\Helpers\RandomStringHelper;
use App\Shared\Libraries\Uuid;
use App\Shared\Utils\Hash;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Collection as SupportCollection;

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
                User::ID       => Uuid::uuid4Generate(),
            ]
        ]);
    }

    public static function showUser(
        string $id = null,
        string $profileUniqueName = null,
    ): object
    {
        return (object) ([
            User::ID        => !is_null($id) ? $id : Uuid::uuid4Generate(),
            User::AVATAR_ID => Uuid::uuid4Generate(),
            User::NAME      => "UserName",
            User::EMAIL     => "email.example@email.com",
            User::PASSWORD  => "$2y$10$3D5HkxDb1U1qGxldZ6Bi6eCLrmRE4U8wXoRFfm4vWCYoJP1toiRGa",
            User::ACTIVE    => true,
            'person'        => (object) ([
                Person::ID    => Uuid::uuid4Generate(),
                Person::PHONE => '51998765432'
            ]),
            'profile'      => Collection::make([
                (object) ([
                    Profile::ID    => Uuid::uuid4Generate(),
                    Profile::UNIQUE_NAME => $profileUniqueName
                ])
            ]),
            'image' => (object) ([
                Image::ID => Uuid::uuid4Generate(),
                Image::PATH => 'test',
                Image::TYPE => TypeUploadImageEnum::USER_AVATAR->value
            ]),
        ]);
    }

    public static function getAdminUserInAuth(string $pass = null, bool $active = true): object
    {
        if(is_null($pass))
        {
            $pass = RandomStringHelper::alnumGenerate();
        }

        $userId = Uuid::uuid4Generate();

        return (object) ([
            User::NAME      => "UserName",
            User::EMAIL     => "email.example@email.com",
            User::AVATAR_ID => null,
            User::PASSWORD  => Hash::generateHash($pass),
            User::ACTIVE    => $active,
            User::ID        => $userId,
            'adminUser'     => (object) ([
                AdminUser::ID => Uuid::uuid4Generate(),
                AdminUser::USER_ID => $userId
            ]),
            'profile' => DatabaseCollection::make([
                (object) ([
                    Profile::ID => Uuid::uuid4Generate(),
                    Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER->value
                ]),
            ]),
            'module' => DatabaseCollection::make([
                (object) ([
                    Module::ID => Uuid::uuid4Generate(),
                    Module::MODULE_UNIQUE_NAME => ModulesUniqueNameEnum::USERS->value,
                ])
            ]),
        ]);
    }

    public static function getPersonCreated(): object
    {
        return (object) ([
            Person::ID => Uuid::uuid4Generate(),
            Person::PHONE => '51999999999',
            Person::ZIP_CODE => '00000000',
            Person::ADDRESS => 'test',
            Person::NUMBER_ADDRESS => '23',
            Person::COMPLEMENT => '',
            Person::DISTRICT => 'test',
            Person::UF => 'RS',
            Person::CITY_ID => Uuid::uuid4Generate(),
        ]);
    }
}
