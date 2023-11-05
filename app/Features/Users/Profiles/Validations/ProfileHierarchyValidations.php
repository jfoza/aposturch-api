<?php

namespace App\Features\Users\Profiles\Validations;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Shared\Enums\MessagesEnum;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class ProfileHierarchyValidations
{
    /**
     * @throws AppException
     */
    public static function adminMasterValidation(array $profilesUniqueName): void
    {
        $allowedProfiles = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        foreach ($profilesUniqueName as $profileUniqueName)
        {
            if(!in_array($profileUniqueName, $allowedProfiles))
            {
                throw new AppException(
                    MessagesEnum::PROFILE_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * @throws AppException
     */
    public static function adminChurchValidation(array $profilesUniqueName): void
    {
        $allowedProfiles = [
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        foreach ($profilesUniqueName as $profileUniqueName)
        {
            if(!in_array($profileUniqueName, $allowedProfiles))
            {
                throw new AppException(
                    MessagesEnum::PROFILE_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * @throws AppException
     */
    public static function adminModuleValidation(array $profilesId): void
    {
        $allowedProfiles = [
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        foreach ($profilesId as $profileId)
        {
            if(!in_array($profileId, $allowedProfiles))
            {
                throw new AppException(
                    MessagesEnum::PROFILE_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    /**
     * @throws AppException
     */
    public static function assistantValidation(array $profilesId): void
    {
        $allowedProfiles = [
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        foreach ($profilesId as $profileId)
        {
            if(!in_array($profileId, $allowedProfiles))
            {
                throw new AppException(
                    MessagesEnum::PROFILE_NOT_ALLOWED,
                    Response::HTTP_FORBIDDEN
                );
            }
        }
    }

    public static function supportAuth(Collection $profiles): bool
    {
        return !!$profiles
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value)
            ->first();
    }

    public static function administrativeAuth(Collection $profiles): bool
    {
        return !!$profiles
            ->where(Profile::UNIQUE_NAME, ProfileUniqueNameEnum::ADMIN_MASTER->value)
            ->first();
    }

    public static function boardAuth(Collection $profiles): bool
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
