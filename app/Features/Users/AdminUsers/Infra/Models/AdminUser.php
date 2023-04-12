<?php

namespace App\Features\Users\AdminUsers\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';

    protected $table = 'users.admin_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
