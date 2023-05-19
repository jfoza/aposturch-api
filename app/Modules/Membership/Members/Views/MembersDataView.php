<?php

namespace App\Modules\Membership\Members\Views;

use Illuminate\Database\Eloquent\Model;

class MembersDataView extends Model
{
    const MEMBER_ID               = 'member_id';
    const MEMBER_CODE             = 'member_code';
    const MEMBER_TYPE_ID          = 'member_type_id';
    const MEMBER_TYPE_UNIQUE_NAME = 'member_type_unique_name';
    const USER_ID                 = 'user_id';
    const PERSON_ID               = 'person_id';
    const PROFILE_ID              = 'profile_id';
    const PROFILE_DESCRIPTION     = 'profile_description';
    const PROFILE_UNIQUE_NAME     = 'profile_unique_name';
    const NAME                    = 'name';
    const EMAIL                   = 'email';
    const PHONE                   = 'phone';
    const ADDRESS                 = 'address';
    const NUMBER_ADDRESS          = 'number_address';
    const COMPLEMENT              = 'complement';
    const DISTRICT                = 'district';
    const ZIP_CODE                = 'zip_code';
    const USER_CITY_ID            = 'user_city_id';
    const USER_CITY_DESCRIPTION   = 'user_city_description';
    const UF = 'uf';
    const USER_ACTIVE             = 'user_active';
    const USER_CREATED_AT         = 'user_created_at';
    const CHURCHES                = 'churches';

    protected $table = 'membership.get_members_data_view';

    protected $casts = [
        self::CHURCHES => 'array',
    ];
}
