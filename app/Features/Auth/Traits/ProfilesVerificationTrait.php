<?php

namespace App\Features\Auth\Traits;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use Illuminate\Support\Collection;

trait ProfilesVerificationTrait
{
    private function supportAuth(Collection $profiles): bool
    {
        return !!$profiles
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value)
            ->first();
    }

    private function administrativeAuth(Collection $profiles): bool
    {
        return !!$profiles
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER->value)
            ->first();
    }

    private function boardAuth(Collection $profiles): bool
    {
        $profilesArr = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ];

        return !!$profiles
            ->whereIn(Profile::UNIQUE_NAME, $profilesArr)
            ->first();
    }
}
