<?php

namespace App\Modules\Membership\ResponsibleChurch\Models;

use App\Features\Base\Infra\Models\Register;

class ResponsibleChurch extends Register
{
    const ID = 'id';
    const ADMIN_USER_ID = 'admin_user_id';
    const CHURCH_ID = 'church_id';

    protected $table = 'members.responsible_church';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
