<?php

namespace App\Base\Validations;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\MessagesEnum;
use Symfony\Component\HttpFoundation\Response;

class ProfileHierarchyValidation
{
    const PROFILES_BY_TECHNICAL_SUPPORT = [
        ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
        ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ProfileUniqueNameEnum::ASSISTANT->value,
        ProfileUniqueNameEnum::MEMBER->value,
    ];

    const PROFILES_BY_ADMIN_MASTER = [
        ProfileUniqueNameEnum::ADMIN_MASTER->value,
        ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ProfileUniqueNameEnum::ASSISTANT->value,
        ProfileUniqueNameEnum::MEMBER->value,
    ];

    const PROFILES_BY_ADMIN_CHURCH = [
        ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ProfileUniqueNameEnum::ASSISTANT->value,
        ProfileUniqueNameEnum::MEMBER->value,
    ];

    const PROFILES_BY_ADMIN_MODULE = [
        ProfileUniqueNameEnum::ADMIN_MODULE->value,
        ProfileUniqueNameEnum::ASSISTANT->value,
        ProfileUniqueNameEnum::MEMBER->value,
    ];

    const PROFILES_BY_ASSISTANT = [
        ProfileUniqueNameEnum::ASSISTANT->value,
        ProfileUniqueNameEnum::MEMBER->value,
    ];

    /**
     * @throws AppException
     */
    public static function technicalSupportInPersistenceValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInPersistence($profilesUniqueName, self::PROFILES_BY_TECHNICAL_SUPPORT);
    }

    /**
     * @throws AppException
     */
    public static function adminMasterInPersistenceValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInPersistence($profilesUniqueName, self::PROFILES_BY_ADMIN_MASTER);
    }

    /**
     * @throws AppException
     */
    public static function adminChurchInPersistenceValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInPersistence($profilesUniqueName, self::PROFILES_BY_ADMIN_CHURCH);
    }

    /**
     * @throws AppException
     */
    public static function adminModuleInPersistenceValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInPersistence($profilesUniqueName, self::PROFILES_BY_ADMIN_MODULE);
    }

    /**
     * @throws AppException
     */
    public static function assistantInPersistenceValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInPersistence($profilesUniqueName, self::PROFILES_BY_ASSISTANT);
    }

    /**
     * @throws AppException
     */
    public static function technicalSupportInListingsValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInListings($profilesUniqueName, self::PROFILES_BY_TECHNICAL_SUPPORT);
    }

    /**
     * @throws AppException
     */
    public static function adminMasterInListingsValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInListings($profilesUniqueName, self::PROFILES_BY_ADMIN_MASTER);
    }

    /**
     * @throws AppException
     */
    public static function adminChurchInListingsValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInListings($profilesUniqueName, self::PROFILES_BY_ADMIN_CHURCH);
    }

    /**
     * @throws AppException
     */
    public static function adminModuleInListingsValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInListings($profilesUniqueName, self::PROFILES_BY_ADMIN_MODULE);
    }

    /**
     * @throws AppException
     */
    public static function assistantInListingsValidation(array $profilesUniqueName): void
    {
        self::handleBaseValidationInListings($profilesUniqueName, self::PROFILES_BY_ASSISTANT);
    }

    /**
     * @throws AppException
     */
    public static function handleBaseValidationInPersistence(array $profilesUniqueName, array $allowedProfiles): void
    {
        foreach ($profilesUniqueName as $profileUniqueName)
        {
            if(!in_array($profileUniqueName, $allowedProfiles))
            {
                self::dispatchExceptionProfileNotAllowed();
            }
        }
    }

    /**
     * @throws AppException
     */
    public static function handleBaseValidationInListings(array $profilesUniqueName, array $allowedProfiles): void
    {
        foreach ($profilesUniqueName as $profileUniqueName)
        {
            if(in_array($profileUniqueName, $allowedProfiles))
            {
                return;
            }
            else
            {
                self::dispatchExceptionProfileNotAllowed();
            }
        }
    }

    /**
     * @throws AppException
     */
    public static function dispatchExceptionProfileNotAllowed()
    {
        throw new AppException(
            MessagesEnum::PROFILE_NOT_ALLOWED,
            Response::HTTP_FORBIDDEN
        );
    }
}
