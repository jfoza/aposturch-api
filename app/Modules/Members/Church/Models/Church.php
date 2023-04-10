<?php

namespace App\Modules\Members\Church\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\City\Cities\Infra\Models\City;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\UserChurch\Infra\Models\UserChurch;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Church extends Register
{
    const ID             = 'id';
    const NAME           = 'name';
    const PHONE          = 'phone';
    const EMAIL          = 'email';
    const YOUTUBE        = 'youtube';
    const FACEBOOK       = 'facebook';
    const INSTAGRAM      = 'instagram';
    const IMAGE          = 'image';
    const ZIP_CODE       = 'zip_code';
    const ADDRESS        = 'address';
    const NUMBER_ADDRESS = 'number_address';
    const COMPLEMENT     = 'complement';
    const DISTRICT       = 'district';
    const UF             = 'uf';
    const CITY_ID        = 'city_id';
    const ACTIVE         = 'active';

    protected $table = 'members.churches';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public $hidden = ['pivot'];

    protected $fillable = [
        self::NAME,
        self::PHONE,
        self::EMAIL,
        self::YOUTUBE,
        self::FACEBOOK,
        self::INSTAGRAM,
        self::IMAGE,
        self::ZIP_CODE,
        self::ADDRESS,
        self::NUMBER_ADDRESS,
        self::COMPLEMENT,
        self::DISTRICT,
        self::UF,
        self::CITY_ID,
        self::ACTIVE,
    ];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, self::CITY_ID, City::ID);
    }

    public function adminUser(): HasMany
    {
        return $this->hasMany(AdminUser::class);
    }
}
