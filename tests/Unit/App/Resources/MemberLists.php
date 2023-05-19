<?php

namespace Tests\Unit\App\Resources;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\Members\Views\MembersDataView;
use App\Shared\Libraries\Uuid;

class MemberLists
{
    public static function memberCreated(): object
    {
        return (object) ([
            Member::ID => Uuid::uuid4Generate(),
        ]);
    }

    public static function getMemberDataView(?string $profile = null): object
    {
        if(is_null($profile))
        {
            $profile = ProfileUniqueNameEnum::ADMIN_CHURCH->value;
        }

        return (object) ([
            MembersDataView::MEMBER_ID => Uuid::uuid4Generate(),
            MembersDataView::USER_ID => Uuid::uuid4Generate(),
            MembersDataView::PERSON_ID => Uuid::uuid4Generate(),
            MembersDataView::PROFILE_DESCRIPTION => 'Admin Igreja',
            MembersDataView::PROFILE_UNIQUE_NAME => $profile,
            MembersDataView::NAME => 'test',
            MembersDataView::EMAIL => 'test@test.com',
            MembersDataView::PHONE => '5198765217',
            MembersDataView::ADDRESS => 'test',
            MembersDataView::NUMBER_ADDRESS => 'test',
            MembersDataView::COMPLEMENT => '',
            MembersDataView::DISTRICT => 'test',
            MembersDataView::ZIP_CODE => '00000000',
            MembersDataView::USER_CITY_DESCRIPTION => Uuid::uuid4Generate(),
            MembersDataView::UF => 'RS'
        ]);
    }
}
