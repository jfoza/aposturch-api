<?php

namespace App\Features\Users\Users\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Module\Modules\Infra\Models\Module;
use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\CustomerUsers\Infra\Models\CustomerUser;
use App\Features\Users\EmailVerification\Infra\Models\EmailVerification;
use App\Features\Users\ForgotPassword\Infra\Models\ForgotPassword;
use App\Features\Users\ModulesUsers\Infra\Models\ModuleUser;
use App\Features\Users\NewPasswordGenerations\Infra\Models\NewPasswordGenerations;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Sessions\Infra\Models\Session;
use App\Features\Users\UserChurch\Infra\Models\UserChurch;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizeContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticateContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User
    extends Register
    implements AuthenticateContract, AuthorizeContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    const ID        = 'id';
    const PERSON_ID = 'person_id';
    const NAME      = 'name';
    const EMAIL     = 'email';
    const PASSWORD  = 'password';
    const AVATAR    = 'avatar';
    const ACTIVE    = 'active';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'users.users';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::ID,
        self::PERSON_ID,
        self::NAME,
        self::EMAIL,
        self::PASSWORD,
        self::ACTIVE,
    ];

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function person(): BelongsTo
    {
        return $this->belongsTo(Person::class)->with('city');
    }

    public function adminUser(): HasOne
    {
        return $this->hasOne(AdminUser::class);
    }

    public function customerUser(): HasOne
    {
        return $this->hasOne(CustomerUser::class);
    }

    public function session(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function emailVerification(): HasMany
    {
        return $this->hasMany(EmailVerification::class);
    }

    public function forgotPassword(): HasMany
    {
        return $this->hasMany(ForgotPassword::class);
    }

    public function newPasswordGenerations(): HasMany
    {
        return $this->hasMany(NewPasswordGenerations::class);
    }

    public function profile (): BelongsToMany
    {
        return $this->belongsToMany(
            Profile::class,
            ProfileUser::tableName(),
            ProfileUser::USER_ID,
            ProfileUser::PROFILE_ID
        );
    }

    public function module(): BelongsToMany
    {
        return $this->belongsToMany(
            Module::class,
            ModuleUser::tableName(),
            ModuleUser::USER_ID,
            ModuleUser::MODULE_ID
        );
    }
}
