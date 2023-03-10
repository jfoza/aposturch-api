<?php

namespace App\Features\Users\ForgotPassword\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ForgotPassword extends Register
{
    use HasFactory;

    const ID       = 'id';
    const USER_ID  = 'user_id';
    const CODE     = 'code';
    const VALIDATE = 'validate';
    const ACTIVE   = 'active';

    protected $table = 'users.forgot_password';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::CODE,
        self::VALIDATE,
        self::ACTIVE,
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class, self::USER_ID, User::ID);
    }
}
