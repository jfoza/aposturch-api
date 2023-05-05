<?php

namespace App\Modules\Membership\ChurchesMembers\Models;

use App\Features\Base\Infra\Models\Register;

class ChurchMember extends Register
{
    const ID = 'id';
    const MEMBER_ID = 'member_id';
    const CHURCH_ID = 'church_id';

    protected $table = 'membership.churches_members';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
