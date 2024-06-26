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

    public static function getMemberIdAssertion(): array
    {
        return [
            'userId',
            'name',
            'email',
            'phone',
            'active',
            'zipCode',
            'address',
            'numberAddress',
            'complement',
            'district',
            'cityId',
            'cityDescription',
            'uf',
            'profileId',
            'profileDescription',
            'church',
            'modules',
        ];
    }
}
