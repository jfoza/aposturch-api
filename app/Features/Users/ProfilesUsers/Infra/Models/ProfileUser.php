<?php

namespace App\Features\Users\ProfilesUsers\Infra\Models;

use App\Base\Infra\Models\Register;

class ProfileUser extends Register
{
    const ID = 'id';
    const USER_ID = 'user_id';
    const PROFILE_ID = 'profile_id';

    protected $table = 'users.profiles_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::PROFILE_ID,
    ];
}
