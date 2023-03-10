<?php

namespace App\Features\Users\Rules\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesRules\Infra\Models\ProfileRule;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Rule extends Register
{
    const ID = 'id';
    const DESCRIPTION = 'description';
    const SUBJECT = 'subject';
    const ACTION = 'action';
    const ACTIVE = 'active';

    protected $table = 'users.rules';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [];

    public function profile (): BelongsToMany
    {
        return $this->belongsToMany(
            Profile::class,
            ProfileRule::tableName(),
            ProfileRule::RULE_ID,
            ProfileRule::PROFILE_ID,
        );
    }
}
