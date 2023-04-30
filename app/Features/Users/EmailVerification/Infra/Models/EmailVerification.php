<?php

namespace App\Features\Users\EmailVerification\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailVerification extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const CODE = 'code';
    const ACTIVE = 'active';
    const VALIDATE = 'validate';

    protected $table = 'users.email_verification';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::CODE,
        self::ACTIVE,
        self::VALIDATE,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
