<?php

namespace Tests\Unit\App\Resources;

use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Infra\Models\Image;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\Users\Infra\Models\User;
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

    public static function showChurch(?string $id = null): object
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
            'adminUser' => []
        ]);
    }

    public static function showChurchByUniqueName(?string $uniqueName = null): object
    {
        if(is_null($uniqueName))
        {
            $uniqueName = 'test';
        }

        return (object) ([
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
            'user' => [],
            'imagesChurch' => [],
        ]);
    }

    public static function showChurchWithImage(?string $imageId = null): object
    {
        $id = Uuid::uuid4()->toString();

        if(is_null($imageId))
        {
            $imageId = Uuid::uuid4()->toString();
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
            'adminUser' => [],
            'imagesChurch' => [
                (object) ([
                    Image::ID => $imageId,
                    Image::TYPE => TypeUploadImageEnum::CHURCH->value,
                    Image::PATH => 'product/example.png',
                ])
            ],
        ]);
    }

    public static function getImageCreated(?string $imageId = null): Image
    {
        if(is_null($imageId))
        {
            $imageId = Uuid::uuid4()->toString();
        }

        return Image::make([
            Image::ID => $imageId,
            Image::TYPE => TypeUploadImageEnum::CHURCH->value,
            Image::PATH => 'product/example.png',
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
            'adminUser' => []
        ]);
    }

    public static function showChurchWithResponsible(?string $id = null): mixed
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
