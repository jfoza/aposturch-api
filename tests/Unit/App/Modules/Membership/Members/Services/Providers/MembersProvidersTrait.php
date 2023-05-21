<?php

namespace Tests\Unit\App\Modules\Membership\Members\Services\Providers;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
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
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
                ]
            ],

            'By Admin Module' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                [
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
                ]
            ],

            'By Assistant' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                [
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
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
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
                ]
            ],

            'By Admin Church' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value,
                [
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
                ]
            ],

            'By Admin Module' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value,
                [
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
                ]
            ],

            'By Assistant' => [
                RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value,
                [
                    [
                        "church_id" => $this->defaultChurchId,
                        "church_name" => "Igreja Teste 1",
                        "church_unique_name" => "igreja-teste-1",
                        "church_phone" => "51999999999",
                        "church_email" => "ibvcx@gmail.com",
                        "church_active" => true,
                    ]
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
