<?php

namespace App\Features\Users\UserChurch\Infra\Models;

use App\Features\Base\Infra\Models\Register;

class UserChurch extends Register
{
    const ID        = 'id';
    const CHURCH_ID = 'church_id';
    const USER_ID   = 'user_id';

    protected $table = 'users.user_church';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';
}
