<?php

namespace App\Features\Users\ProfilesRules\Infra\Models;

use App\Features\Base\Infra\Models\Register;

class ProfileRule extends Register
{
    const ID = 'id';
    const RULE_ID = 'rule_id';
    const PROFILE_ID = 'profile_id';

    protected $table = 'users.profiles_rules';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::RULE_ID,
        self::PROFILE_ID,
    ];
}
