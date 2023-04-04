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
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT
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
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT,
            ProfileUniqueNameEnum::ADMIN_MASTER,
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }

    /**
     * @throws AppException
     */
    public static function validateAdminDepartmentProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT,
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::ADMIN_CHURCH,
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
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT,
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            self::handleException();
        }
    }
}
