<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\Profiles\Infra\Models\Profile;
use Ramsey\Uuid\Uuid;

class ProfilesLists
{
    public static function getAdminMasterProfile(string $profileId = null): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Admin Master',
            Profile::UNIQUE_NAME => 'ADMIN_MASTER',
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
            Profile::UNIQUE_NAME => 'ADMIN_CHURCH',
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
            Profile::UNIQUE_NAME => 'ADMIN_MODULE',
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
            Profile::UNIQUE_NAME => 'ASSISTANT',
        ]);
    }
}
