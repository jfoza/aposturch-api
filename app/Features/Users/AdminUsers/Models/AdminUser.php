<?php

namespace App\Features\Users\AdminUsers\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\ResponsibleChurch\Models\ResponsibleChurch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AdminUser extends Register
{
    use HasFactory;

    const ID = 'id';
    const USER_ID = 'user_id';
    const CREATED_AT = 'created_at';

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

    public function responsibleChurch(): BelongsToMany
    {
        return $this->belongsToMany(
            Church::class,
            ResponsibleChurch::class,
            ResponsibleChurch::ADMIN_USER_ID,
            ResponsibleChurch::CHURCH_ID,
        );
    }
}
