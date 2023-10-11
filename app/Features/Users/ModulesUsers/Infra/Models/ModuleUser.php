<?php

namespace App\Features\Users\ModulesUsers\Infra\Models;

use App\Base\Infra\Models\Register;

class ModuleUser extends Register
{
    const ID        = 'id';
    const MODULE_ID = 'module_id';
    const USER_ID   = 'user_id';

    protected $table = 'users.modules_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::MODULE_ID,
        self::USER_ID,
    ];
}
