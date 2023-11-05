<?php

namespace Tests\Unit\App\Modules\Membership\Members;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\RulesEnum;

trait MembersProvidersTrait
{
    public static function dataProviderListMembers(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW->value],
        ];
    }

    public static function dataProviderListMembersValidations(): array
    {
        return [
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW->value],
        ];
    }

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

    public static function dataProviderInsertNewMemberProfilesModulesValidation(): array
    {
        return [
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_INSERT->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_INSERT->value],
        ];
    }

    public static function dataProviderUpdate(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value],
        ];
    }

    public static function dataProviderUpdateMemberValidationChurch(): array
    {
        return [
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value],
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value],
        ];
    }

    public static function dataProviderUpdateMemberWithAdminMasterAndAdminChurch(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value],
        ];
    }

    public static function dataProviderUpdateMemberValidationProfileAndModules(): array
    {
        return [
            'By Admin Module' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value],
            'By Assistant'    => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value],
        ];
    }

    public static function dataProviderUpdateChurchData(): array
    {
        return [
            'By Admin Master' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value],
            'By Admin Church' => [RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value],
        ];
    }
}
