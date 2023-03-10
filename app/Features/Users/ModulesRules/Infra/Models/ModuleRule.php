<?php

namespace App\Features\Users\ModulesRules\Infra\Models;

use App\Features\Base\Infra\Models\Register;

class ModuleRule extends Register
{
    const ID        = 'id';
    const MODULE_ID = 'module_id';
    const RULE_ID   = 'rule_id';

    protected $table = 'users.modules_rules';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::MODULE_ID,
        self::RULE_ID,
    ];
}
