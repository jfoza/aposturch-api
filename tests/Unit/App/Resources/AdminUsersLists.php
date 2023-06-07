<?php

namespace Tests\Unit\App\Resources;

use App\Features\Module\Modules\Models\Module;
use Illuminate\Support\Collection as CollectionSupport;
use Ramsey\Uuid\Uuid;

class AdminUsersLists
{
    public static function getAllAdminUsers(): CollectionSupport
    {
        return CollectionSupport::make([
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

    public static function getUniqueAdminUser(): CollectionSupport
    {
        return CollectionSupport::make([
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
            'module' => CollectionSupport::make([
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
