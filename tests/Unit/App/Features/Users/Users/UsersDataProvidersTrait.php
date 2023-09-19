<?php

namespace Tests\Unit\App\Features\Users\Users;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

trait UsersDataProvidersTrait
{
    public static function dataProviderUploadImageDifferentUser(): array
    {
        return [
            'By Admin Church Rule' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_CHURCH->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value
            ],
            'By Admin Module Rule' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value
            ],
            'By Assistant'         => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value
            ],
        ];
    }

    public static function dataProviderUploadImageModulesValidation(): array
    {
        return [
            'By Admin Module Rule' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MODULE->value,
            ],
            'By Assistant' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT->value,
            ],
        ];
    }

    public static function dataProviderUploadImageMemberItself(): array
    {
        return [
            'By Admin Church Rule case' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_CHURCH->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value
            ],
            'By Admin Module Rule' => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value
            ],
            'By Assistant'         => [
                RulesEnum::USERS_IMAGE_UPLOAD_ADMIN_ASSISTANT->value,
                ProfileUniqueNameEnum::ASSISTANT->value
            ],
        ];
    }
}
