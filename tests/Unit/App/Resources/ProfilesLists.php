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
            Profile::UNIQUE_NAME => 'admin-master',
        ]);
    }

    public static function getEmployeeProfile(string|null $profileId): object
    {
        if(is_null($profileId))
        {
            $profileId = Uuid::uuid4()->toString();
        }

        return (object) ([
            Profile::ID          => $profileId,
            Profile::DESCRIPTION => 'Colaborador',
            Profile::UNIQUE_NAME => 'employee',
        ]);
    }
}
