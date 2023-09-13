<?php

namespace Tests\Feature\Resources\Modules\Members;

class MembersAssertions
{
    public static function getMemberAssertion(): array
    {
        return [
            'id',
            'member_id',
            'member_code',
            'user_id',
            'name',
            'email',
            'avatar_id',
            'active',
            'user_created_at',
            'profile_id',
            'profile_description',
            'profile_unique_name',
            'person_id',
            'phone',
            'address',
            'number_address',
            'complement',
            'district',
            'zip_code',
            'city_id',
            'city_description',
            'uf',
            'church'
        ];
    }
}


//"id" => "4e06df45-6e53-4474-835b-dc107328c2c8"
//  "person_id" => "efe1e9a7-d807-437c-9af3-2e658476ecf9"
//  "avatar_id" => null
//  "name" => "Usuário Admin Módulo"
//  "email" => "admin-module1@email.com"
//  "password" => "$2a$12$VeNeN4sJ0nK0Q.FTts2N7O6w6/EfFxJ3E4mhtako05iBSS8GHjiuG"
//  "active" => true
//  "created_at" => "2023-08-24T13:54:40.125012Z"
//  "updated_at" => "2023-08-24T13:54:40.125012Z"
