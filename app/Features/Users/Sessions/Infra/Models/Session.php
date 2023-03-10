<?php

namespace App\Features\Users\Sessions\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Register
{
    const ID           = 'id';
    const USER_ID      = 'user_id';
    const INITIAL_DATE = 'initial_date';
    const FINAL_DATE   = 'final_date';
    const TOKEN        = 'token';
    const IP_ADDRESS   = 'ip_address';

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
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }
}
