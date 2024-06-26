<?php

namespace Tests\Unit\App\Resources;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\ResponsibleChurch\Models\ResponsibleChurch;
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

    public static function getChurchesById(string $churchId): Collection
    {
        return Collection::make([
            [
                Church::ID             => $churchId,
                Church::NAME           => 'test',
                Church::PHONE          => '51999999999',
                Church::EMAIL          => 'test@test.com',
                Church::FACEBOOK       => '',
                Church::INSTAGRAM      => '',
                Church::YOUTUBE        => '',
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

    public static function getChurchesByIdArray(string $churchId): object
    {
        return (object) ([
                Church::ID             => $churchId,
                Church::NAME           => 'test',
                Church::PHONE          => '51999999999',
                Church::EMAIL          => 'test@test.com',
                Church::FACEBOOK       => '',
                Church::INSTAGRAM      => '',
                Church::YOUTUBE        => '',
                Church::ZIP_CODE       => '99999999',
                Church::ADDRESS        => 'test',
                Church::NUMBER_ADDRESS => 'test',
                Church::COMPLEMENT     => 'test',
                Church::DISTRICT       => 'test',
                Church::UF             => 'RS',
                Church::CITY_ID        => Uuid::uuid4()->toString(),
                Church::ACTIVE         => true,
            ]);
    }

    public static function getChurchesByUniqueName(string $uniqueName): Collection
    {
        return Collection::make([
            [
                Church::ID             => Uuid::uuid4()->toString(),
                Church::NAME           => 'test',
                Church::UNIQUE_NAME    => $uniqueName,
                Church::PHONE          => '51999999999',
                Church::EMAIL          => 'test@test.com',
                Church::FACEBOOK       => '',
                Church::INSTAGRAM      => '',
                Church::YOUTUBE        => '',
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

    public static function showChurch(string $id = null): object
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
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'user' => [],
            'imagesChurch' => [],
            'member' => []
        ]);
    }

    public static function showChurchWithImage(?string $churchId = null, ?string $imageId = null): object
    {
        if(is_null($churchId))
        {
            $churchId = Uuid::uuid4()->toString();
        }

        if(is_null($imageId))
        {
            $imageId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Church::ID             => $churchId,
            Church::NAME           => 'test',
            Church::PHONE          => '51999999999',
            Church::EMAIL          => 'test@test.com',
            Church::FACEBOOK       => '',
            Church::INSTAGRAM      => '',
            Church::YOUTUBE        => '',
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'user' => [],
            'member' => [],
            'imagesChurch' => [
                (object) ([
                    Image::ID => $imageId,
                    Image::TYPE => TypeUploadImageEnum::CHURCH->value,
                    Image::PATH => 'product/example.png',
                ])
            ],
        ]);
    }

    public static function showChurchWithMembers(?string $id = null): object
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
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'user' => [
                User::make([
                    User::ID => Uuid::uuid4()->toString(),
                ]),
            ],
            'member' => [
                [
                    Member::ID => Uuid::uuid4()->toString(),
                ]
            ]
        ]);
    }

    public static function showChurchWithResponsible(?string $id = null): object
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
            Church::ZIP_CODE       => '99999999',
            Church::ADDRESS        => 'test',
            Church::NUMBER_ADDRESS => 'test',
            Church::COMPLEMENT     => 'test',
            Church::DISTRICT       => 'test',
            Church::UF             => 'RS',
            Church::CITY_ID        => Uuid::uuid4()->toString(),
            Church::ACTIVE         => true,
            'user' => [],
            'adminUser' => [
                AdminUser::make([
                    AdminUser::ID => Uuid::uuid4()->toString(),
                ]),
            ],
        ]);
    }
}
