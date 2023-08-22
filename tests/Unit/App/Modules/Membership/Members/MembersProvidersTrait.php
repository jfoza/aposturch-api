<?php

namespace Tests\Unit\App\Modules\Membership\Members;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Shared\Enums\RulesEnum;

trait MembersProvidersTrait
{
    public string $defaultChurchId = 'e0365ae3-f334-47b9-bb49-55507f6e4304';

    public function dataProviderUpdateUserMemberItself(): array
    {
        return [
            'By Admin Church' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],

            'By Admin Module' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],

            'By Assistant' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],
        ];
    }

    public function dataProviderUpdateUniqueUserMember(): array
    {
        return [
            'By Admin Master' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],

            'By Admin Church' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],

            'By Admin Module' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],

            'By Assistant' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                [
                    (object)([
                        Church::ID          => $this->defaultChurchId,
                        Church::NAME        => "Igreja Teste 1",
                        Church::UNIQUE_NAME => "igreja-teste-1",
                        Church::PHONE       => "51999999999",
                        Church::EMAIL       => "ibvcx@gmail.com",
                        Church::ACTIVE      => true,
                    ])
                ]
            ],
        ];
    }

    public function dataProviderUpdateUserMemberProfilesNotAllowed(): array
    {
        return [
            'By Admin Church' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value
            ],

            'By Admin Module 1' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value
            ],

            'By Admin Module 2' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value
            ],

            'By Assistant' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                ProfileUniqueNameEnum::ASSISTANT->value
            ],

            'By Assistant 2' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value
            ],

            'By Assistant 3' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value
            ],
        ];
    }
}
