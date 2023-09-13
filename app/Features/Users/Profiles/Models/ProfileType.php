<?php

namespace App\Features\Users\Profiles\Models;

use App\Features\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileType extends Register
{
    const ID = 'id';
    const DESCRIPTION = 'description';
    const UNIQUE_NAME = 'unique_name';

    protected $table = 'users.profile_types';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public function profile(): HasMany
    {
        return $this->hasMany(Profile::class, Profile::PROFILE_TYPE_ID, self::ID);
    }
}
