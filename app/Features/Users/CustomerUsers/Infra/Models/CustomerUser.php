<?php

namespace App\Features\Users\CustomerUsers\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const VERIFIED_EMAIL = 'verified_email';

    protected $table = 'users.customer_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::VERIFIED_EMAIL,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
