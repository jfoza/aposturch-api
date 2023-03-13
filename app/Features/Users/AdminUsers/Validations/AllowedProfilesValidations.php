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
    public static function validateEmployeeProfile(string $profileUniqueName): void
    {
        $profilesNotAllowed = [
            ProfileUniqueNameEnum::ADMIN_MASTER
        ];

        if(in_array($profileUniqueName, $profilesNotAllowed)) {
            throw new AppException(
                MessagesEnum::NOT_AUTHORIZED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
