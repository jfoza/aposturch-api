<?php

namespace App\Features\Users\AdminUsers\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Infra\Models\User;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const CHURCH_ID = 'church_id';

    protected $table = 'users.admin_users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::USER_ID,
        self::CHURCH_ID,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }
}
