<?php

namespace App\Features\Users\Sessions\Models;

use App\Base\Infra\Models\Register;
use App\Features\Users\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Register
{
    const ID           = 'id';
    const USER_ID      = 'user_id';
    const INITIAL_DATE = 'initial_date';
    const FINAL_DATE   = 'final_date';
    const TOKEN        = 'token';
    const IP_ADDRESS   = 'ip_address';
    const AUTH_TYPE    = 'auth_type';
    const ACTIVE       = 'active';

    protected $table = 'users.sessions';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::ID,
        self::USER_ID,
        self::INITIAL_DATE,
        self::FINAL_DATE,
        self::TOKEN,
        self::IP_ADDRESS,
        self::AUTH_TYPE,
        self::ACTIVE,
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }
}
