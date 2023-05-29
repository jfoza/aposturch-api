<?php

namespace Tests\Unit\App\Resources;

use App\Modules\Membership\Members\Enums\MembersDataAliasEnum;
use App\Modules\Membership\Members\Models\Member;
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
            MembersDataAliasEnum::MEMBER_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::USER_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::PERSON_ID => Uuid::uuid4Generate(),
            MembersDataAliasEnum::NAME => 'test',
            MembersDataAliasEnum::EMAIL => 'test@test.com',
            MembersDataAliasEnum::PHONE => '5198765217',
            MembersDataAliasEnum::ADDRESS => 'test',
            MembersDataAliasEnum::NUMBER_ADDRESS => 'test',
            MembersDataAliasEnum::COMPLEMENT => '',
            MembersDataAliasEnum::DISTRICT => 'test',
            MembersDataAliasEnum::ZIP_CODE => '00000000',
            MembersDataAliasEnum::USER_CITY_DESCRIPTION => Uuid::uuid4Generate(),
            MembersDataAliasEnum::UF => 'RS',
            'church' => $churches
        ]);
    }
}
