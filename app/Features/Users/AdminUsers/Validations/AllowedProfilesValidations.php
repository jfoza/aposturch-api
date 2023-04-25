<?php

namespace App\Features\Users\AdminUsers\Validations;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class AllowedProfilesValidations
{
    /**
     * @throws AppException
     */
    public static function handleException()
    {
        throw new AppException(
            MessagesEnum::NOT_AUTHORIZED,
            Response::HTTP_FORBIDDEN
        );
    }

    /**
     * @throws AppException
     */
    public static function validateAdminMasterProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }

    /**
     * @throws AppException
     */
    public static function validateAdminChurchProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }

    /**
     * @throws AppException
     */
    public static function validateAdminModuleProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }

    /**
     * @throws AppException
     */
    public static function validateAssistantProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }
}
