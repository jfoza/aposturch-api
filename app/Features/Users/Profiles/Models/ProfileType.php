<?php

namespace App\Features\Users\Profiles\Models;

use App\Features\Base\Infra\Models\Register;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProfileType extends Register
{
    const ID = 'id';

    protected $table = 'users.profile_types';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [];

    public function profile(): HasMany
    {
        return $this->hasMany(Profile::class, Profile::PROFILE_TYPE_ID, self::ID);
    }
}
