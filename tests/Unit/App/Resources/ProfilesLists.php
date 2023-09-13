<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class ProfilesLists
{
    public static function getAllProfiles(): Collection
    {
        return Collection::make([
            [
                Profile::ID          => Uuid::uuid4()->toString(),
                Profile::DESCRIPTION => 'Admin Master',
                Profile::UNIQUE_NAME => 'ADMIN_MASTER',
            ]
        ]);
    }

    public static function getAdminTechnicalSupportProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Suporte Técnico',
            Profile::UNIQUE_NAME => ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
        ]);
    }

    public static function getAdminMasterProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Admin Master',
            Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ]);
    }

    public static function getAdminChurchProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Admin Igreja',
            Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        ]);
    }

    public static function getAdminModuleProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Admin Módulo',
            Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ]);
    }

    public static function getAssistantProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Auxiliar',
            Profile::UNIQUE_NAME => ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }
}
