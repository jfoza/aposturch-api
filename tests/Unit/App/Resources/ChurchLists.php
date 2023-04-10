<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

class ChurchLists
{
    public static function getChurches(): Collection
    {
        return Collection::make([
            [
                Church::ID             => Uuid::uuid4()->toString(),
                Church::NAME           => 'test',
                Church::PHONE          => '51999999999',
                Church::EMAIL          => 'test@test.com',
                Church::FACEBOOK       => '',
                Church::INSTAGRAM      => '',
                Church::YOUTUBE        => '',
                Church::IMAGE          => '',
                Church::ZIP_CODE       => '99999999',
                Church::ADDRESS        => 'test',
                Church::NUMBER_ADDRESS => 'test',
                Church::COMPLEMENT     => 'test',
                Church::DISTRICT       => 'test',
                Church::UF             => 'RS',
                Church::CITY_ID        => Uuid::uuid4()->toString(),
                Church::ACTIVE         => true,
            ]
        ]);
    }

    public static function showChurch(?string $id = null): Church
    {
        if(is_null($id))
        {
            $id = Uuid::uuid4()->toString();
        }

        return Church::make([
            Church::ID             => $id,
            Church::NAME           => 'test',
            Church::PHONE          => '51999999999',
            Church::EMAIL          => 'test@test.com',
            Church::FACEBOOK       => '',
            Church::INSTAGRAM      => '',
            Church::YOUTUBE        => '',
            Church::IMAGE          => '',
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'adminUser' => [],
        ]);
    }

    public static function showChurchWithMembers(?string $id = null): mixed
    {
        if(is_null($id))
        {
            $id = Uuid::uuid4()->toString();
        }

        return (object) ([
            Church::ID             => $id,
            Church::NAME           => 'test',
            Church::PHONE          => '51999999999',
            Church::EMAIL          => 'test@test.com',
            Church::FACEBOOK       => '',
            Church::INSTAGRAM      => '',
            Church::YOUTUBE        => '',
            Church::IMAGE          => '',
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'adminUser' => [
                AdminUser::make([
                    AdminUser::ID         => Uuid::uuid4()->toString(),
                    AdminUser::USER_ID    => Uuid::uuid4()->toString(),
                    AdminUser::CHURCH_ID  => $id,
                ]),
            ],
        ]);
    }
}
