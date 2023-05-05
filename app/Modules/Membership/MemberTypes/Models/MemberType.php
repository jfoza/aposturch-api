<?php

namespace App\Modules\Membership\MemberTypes\Models;

use App\Features\Base\Infra\Models\Register;
use App\Modules\Membership\Members\Models\Member;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberType extends Register
{
    const ID = 'id';
    const UNIQUE_NAME = 'unique_name';
    const DESCRIPTION = 'description';

    protected $table = 'membership.member_types';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public function member(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}
