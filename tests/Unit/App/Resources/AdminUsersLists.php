<?php

namespace Tests\Unit\App\Resources;

use App\Features\Module\Modules\Models\Module;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Shared\Libraries\Uuid;
use Illuminate\Support\Collection;

class AdminUsersLists
{
    public static function getAllAdminUsers(): Collection
    {
        $userId = Uuid::uuid4Generate();

        return Collection::make([
            [
                User::ID        => $userId,
                User::PERSON_ID => Uuid::uuid4Generate(),
                User::AVATAR_ID => "Test",
                User::NAME      => "test@gmail.com",
                User::ACTIVE    => true,
                'adminUser' => (object) ([
                    AdminUser::ID => Uuid::uuid4Generate(),
                    AdminUser::USER_ID => $userId,
                ]),
                'profile' => Collection::make([
                    (object) ([
                        Profile::ID => Uuid::uuid4Generate(),
                        Profile::DESCRIPTION => 'AdminMaster',
                        Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER->value,
                        Profile::ACTIVE => true,
                    ])
                ])
            ]
        ]);
    }

    public static function getUniqueAdminUser(): object
    {
        $userId = Uuid::uuid4Generate();

        return (object) ([
            User::ID        => $userId,
            User::PERSON_ID => Uuid::uuid4Generate(),
            User::AVATAR_ID => "Test",
            User::NAME      => "test@gmail.com",
            User::ACTIVE    => true,
            'adminUser' => (object) ([
                AdminUser::ID => Uuid::uuid4Generate(),
                AdminUser::USER_ID => $userId,
            ]),
            'profile' => Collection::make([
                (object) ([
                    Profile::ID => Uuid::uuid4Generate(),
                    Profile::DESCRIPTION => 'AdminMaster',
                    Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER->value,
                    Profile::ACTIVE => true,
                ])
            ])
        ]);
    }

    public static function getUniqueTechnicalSupportUser(): object
    {
        $userId = Uuid::uuid4Generate();

        return (object) ([
            User::ID        => $userId,
            User::PERSON_ID => Uuid::uuid4Generate(),
            User::AVATAR_ID => "Test",
            User::NAME      => "test@gmail.com",
            User::ACTIVE    => true,
            'adminUser' => (object) ([
                AdminUser::ID => Uuid::uuid4Generate(),
                AdminUser::USER_ID => $userId,
            ]),
            'profile' => Collection::make([
                (object) ([
                    Profile::ID => Uuid::uuid4Generate(),
                    Profile::DESCRIPTION => 'Suporte Técnico',
                    Profile::UNIQUE_NAME => ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
                    Profile::ACTIVE => true,
                ])
            ])
        ]);
    }

    public static function getAdminUserLogged(): object
    {
        return (object) ([
            'id'       => 'edd710bb-55d3-4e0e-8027-d5d68cd1a0f9',
            'email'    => 'usuario@email.com',
            'avatar_id'   => null,
            'name' => 'Name User',
            'active' => true,
            'profile' => collect([
                [
                    "id" => "7f94247d-38a7-424b-ae7a-bb3262a587b9",
                    "profile_type_id" => "3facf59b-175a-4b08-8a85-e1d6cb5b4b06",
                    "description" => "Funcionário",
                    "unique_name" => "employee",
                    "active" => true,
                    "created_at" => "2022-09-15T23:12:53.905905Z",
                    "updated_at" => "2022-09-15T23:12:53.905905Z",
                ]
            ]),
            'module' => Collection::make([
                (object) ([
                    Module::ID => Uuid::uuid4()->toString()
                ])
            ])
        ]);
    }

    public static function getRules(): array
    {
        return [
            [
                'subject' => 'PROFILES_EMPLOYEE',
                'action'  => 'VIEW'
            ],
            [
                'subject' => 'THEMES',
                'action'  => 'DELETE'
            ],
            [
                'subject' => 'USERS',
                'action'  => 'VIEW'
            ],
            [
                'subject' => 'ADMIN_USERS_EMPLOYEE',
                'action'  => 'VIEW'
            ],
            [
                'subject' => 'ADMIN_USERS_EMPLOYEE',
                'action'  => 'INSERT'
            ],
            [
                'subject' => 'ADMIN_USERS_EMPLOYEE',
                'action'  => 'UPDATE'
            ],
        ];
    }
}
