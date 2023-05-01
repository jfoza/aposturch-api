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
    public static function validateSupportProfile(string $profileUniqueName): void
    {
        $profilesAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value
        ];

        if(!in_array($profileUniqueName, $profilesAllowed)) {
            self::handleException();
        }
    }

    /**
     * @throws AppException
     */
    public static function validateAdminMasterProfile(string $profileUniqueName): void
    {
        $profilesAllowed = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value
        ];

        if(!in_array($profileUniqueName, $profilesAllowed)) {
            self::handleException();
        }
    }
}
