<?php

namespace Tests\Unit\App\Modules\Membership\Members;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\RulesEnum;

trait MembersProvidersTrait
{
    public static function dataProviderUpdateStatusMember(): array
    {
        return [
            'By Admin Master' => [RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value],
            'By Admin Church' => [RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value],
            'By Admin Module' => [RulesEnum::USERS_ADMIN_MODULE_UPDATE_STATUS->value],
        ];
    }

    public static function dataProviderMemberProfilesValidations(): array
    {
        return [
            'To Admin Module' => [RulesEnum::USERS_ADMIN_MODULE_UPDATE_STATUS->value],
            'To Admin Church' => [RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value],
        ];
    }

    public static function dataProviderInsertNewMember(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_INSERT->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value],
        ];
    }

    public static function dataProviderInsertNewMemberChurchValidation(): array
    {
        return [
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_INSERT->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value],
        ];
    }

    public static function dataProviderInsertNewMemberProfilesValidation(): array
    {
        return [
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value],
        ];
    }
}
