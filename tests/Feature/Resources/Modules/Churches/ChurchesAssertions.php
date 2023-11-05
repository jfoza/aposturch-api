<?php

namespace Tests\Feature\Resources\Modules\Churches;

class ChurchesAssertions
{
    public static function churchAssertion(): array
    {
        return [
            'id',
            'name',
            'unique_name',
            'phone',
            'email',
            'youtube',
            'facebook',
            'instagram',
            'zip_code',
            'address',
            'number_address',
            'complement',
            'district',
            'uf',
            'city_id',
            'active',
            'created_at',
            'updated_at',
            'city',
        ];
    }

    public static function churchByIdAssertion(): array
    {
        return [
            'id',
            'name',
            'unique_name',
            'phone',
            'email',
            'youtube',
            'facebook',
            'instagram',
            'zip_code',
            'address',
            'number_address',
            'complement',
            'district',
            'uf',
            'city_id',
            'active',
            'created_at',
            'updated_at',
            'image',
            'images_church',
            'city',
        ];
    }
}
