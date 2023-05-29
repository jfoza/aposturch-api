<?php

namespace Tests\Unit\App\Resources;

use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\Members\Utils\MembersDataAlias;
use App\Shared\Libraries\Uuid;

class MemberLists
{
    public static function memberCreated(): object
    {
        return (object) ([
            Member::ID => Uuid::uuid4Generate(),
        ]);
    }

    public static function getMemberDataView(array $churches = []): object
    {
        return (object) ([
            MembersDataAlias::MEMBER_ID => Uuid::uuid4Generate(),
            MembersDataAlias::USER_ID => Uuid::uuid4Generate(),
            MembersDataAlias::PERSON_ID => Uuid::uuid4Generate(),
            MembersDataAlias::NAME => 'test',
            MembersDataAlias::EMAIL => 'test@test.com',
            MembersDataAlias::PHONE => '5198765217',
            MembersDataAlias::ADDRESS => 'test',
            MembersDataAlias::NUMBER_ADDRESS => 'test',
            MembersDataAlias::COMPLEMENT => '',
            MembersDataAlias::DISTRICT => 'test',
            MembersDataAlias::ZIP_CODE => '00000000',
            MembersDataAlias::USER_CITY_DESCRIPTION => Uuid::uuid4Generate(),
            MembersDataAlias::UF => 'RS',
            MembersDataAlias::CHURCHES => $churches
        ]);
    }
}
