<?php

namespace App\Modules\Membership\Members\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\ChurchesMembers\Models\ChurchMember;
use App\Modules\Membership\MemberTypes\Models\MemberType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Member extends Register
{
    use HasFactory;

    const ID             = 'id';
    const USER_ID        = 'user_id';
    const TYPE_MEMBER_ID = 'type_member_id';
    const CODE           = 'code';
    const ACTIVE         = 'active';
    const CREATED_AT     = 'created_at';

    protected $table = 'membership.members';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::TYPE_MEMBER_ID,
        self::CODE,
        self::ACTIVE,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function memberType(): BelongsTo
    {
        return $this->belongsTo(MemberType::class);
    }

    public function church(): BelongsToMany
    {
        return $this->belongsToMany(
            Church::class,
            ChurchMember::tableName(),
            ChurchMember::MEMBER_ID,
            ChurchMember::CHURCH_ID,
        );
    }
}
