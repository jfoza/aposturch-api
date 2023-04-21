<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\Users\Infra\Models\User;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class AdminUsersLists
{
    public static function getAllAdminUsers(): Collection
    {
        return Collection::make([
            [
                "admin_user_id"       => "cacdd8aa-ee9d-4008-8bcc-27cb8fd8ae88",
                "user_id"             => "9d13e085-20f9-4668-b427-7e9142fd7cfe",
                "user_name"           => "Kainara Appelt 30",
                "user_email"          => "kaiappelt453@gmail.com",
                "user_active"         => true,
                "user_created_at"     => "2023-03-12 20:26:12",
                "profile_id"          => "d5f39470-767b-4f35-99ca-ae68224cedee",
                "profile_unique_name" => "employee",
                "profile_description" => "Colaborador"
            ]
        ]);
    }

    public static function getUniqueAdminUser(): Collection
    {
        return Collection::make([
            "admin_user_id"       => "cacdd8aa-ee9d-4008-8bcc-27cb8fd8ae88",
            "user_id"             => "9d13e085-20f9-4668-b427-7e9142fd7cfe",
            "user_name"           => "Kainara Appelt 30",
            "user_email"          => "kaiappelt453@gmail.com",
            "user_active"         => true,
            "user_created_at"     => "2023-03-12 20:26:12",
            "profile_id"          => "d5f39470-767b-4f35-99ca-ae68224cedee",
            "profile_unique_name" => "employee",
            "profile_description" => "Colaborador"
        ]);
    }

    public static function getAdminUserByEmail(bool $active = true): object
    {
        return (object) ([
            AdminUser::ID      => "48fdcbba-86e2-4567-8999-dda3c3e5279c",
            AdminUser::USER_ID => "edd710bb-55d3-4e0e-8027-d5d68cd1a0f9",

            "user" => (object) ([
                User::ID => "edd710bb-55d3-4e0e-8027-d5d68cd1a0f9",
                User::PERSON_ID => null,
                User::NAME      => "Name User",
                User::EMAIL     => "usuario@email.com",
                User::PASSWORD  => "$2a$12$8O6CjzAHu5UY9VtGxNwJH.29v2Qu6Q28IR7CKNESVwKdH5uMbrFmC",
                User::AVATAR    => null,
                User::ACTIVE    => $active,
                "profile" => (object) ([
                    Profile::ID => "7f94247d-38a7-424b-ae7a-bb3262a587b9",
                    Profile::PROFILE_TYPE_ID => "3facf59b-175a-4b08-8a85-e1d6cb5b4b06",
                    Profile::DESCRIPTION => "Funcionário",
                    Profile::UNIQUE_NAME => "employee",
                    Profile::ACTIVE => true,
                ]),
                "church" => Collection::make([
                        [Church::ID => Uuid::uuid4()->toString()]
                    ]),
                "module" => self::getModules(),
            ])
        ]);
    }

    public static function getUnchurchedAdminUserByEmail(bool $active = true): object
    {
        return (object) ([
            AdminUser::ID      => "48fdcbba-86e2-4567-8999-dda3c3e5279c",
            AdminUser::USER_ID => "edd710bb-55d3-4e0e-8027-d5d68cd1a0f9",

            "user" => (object) ([
                User::ID => "edd710bb-55d3-4e0e-8027-d5d68cd1a0f9",
                User::PERSON_ID => null,
                User::NAME      => "Name User",
                User::EMAIL     => "usuario@email.com",
                User::PASSWORD  => "$2a$12$8O6CjzAHu5UY9VtGxNwJH.29v2Qu6Q28IR7CKNESVwKdH5uMbrFmC",
                User::AVATAR    => null,
                User::ACTIVE    => $active,
                "profile" => (object) ([
                    Profile::ID => "7f94247d-38a7-424b-ae7a-bb3262a587b9",
                    Profile::PROFILE_TYPE_ID => "3facf59b-175a-4b08-8a85-e1d6cb5b4b06",
                    Profile::DESCRIPTION => "Funcionário",
                    Profile::UNIQUE_NAME => "employee",
                    Profile::ACTIVE => true,
                ]),
                "church" => Collection::empty(),
                "module" => self::getModules(),
            ])
        ]);
    }

    public static function getAdminUserLogged(): object
    {
        return (object) ([
            'id'       => 'edd710bb-55d3-4e0e-8027-d5d68cd1a0f9',
            'email'    => 'usuario@email.com',
            'avatar'   => null,
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
            'module' => self::getModules()
        ]);
    }

    public static function getModules(): array
    {
        return [];
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
