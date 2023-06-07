<?php

namespace Tests\Unit\App\Features\Users\Users\Services\Providers;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

trait UsersDataProvidersTrait
{
    public function dataProviderUploadImageDifferentUser(): array
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

    public function dataProviderUploadImageMemberItself(): array
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
