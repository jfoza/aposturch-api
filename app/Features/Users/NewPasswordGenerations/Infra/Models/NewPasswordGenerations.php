<?php

namespace App\Features\Users\NewPasswordGenerations\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NewPasswordGenerations extends Register
{
    const ID       = 'id';
    const USER_ID  = 'user_id';
    const CREATED_AT  = 'created_at';

    protected $table = 'users.new_password_generations';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
    ];

    public function user (): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
