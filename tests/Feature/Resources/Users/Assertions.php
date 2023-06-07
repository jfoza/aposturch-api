<?php

namespace Tests\Feature\Resources\Users;

class Assertions
{
    public static function adminUserAssertion(): array
    {
        return [
            'id',
            'person_id',
            'name',
            'email',
            'password',
            'avatar_id',
            'active',
            'created_at',
            'updated_at',
            'admin_user',
            'profile',
        ];
    }
}
