<?php

namespace App\Modules\Members\Church\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\City\Cities\Infra\Models\City;
use App\Features\General\Images\Infra\Models\Image;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\UserChurch\Infra\Models\UserChurch;
use App\Features\Users\Users\Infra\Models\User;
use App\Modules\Members\ChurchesImages\Models\ChurchImage;
use App\Modules\Members\ResponsibleChurch\Models\ResponsibleChurch;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Church extends Register
{
    const ID             = 'id';
    const NAME           = 'name';
    const UNIQUE_NAME    = 'unique_name';
    const PHONE          = 'phone';
    const EMAIL          = 'email';
    const YOUTUBE        = 'youtube';
    const FACEBOOK       = 'facebook';
    const INSTAGRAM      = 'instagram';
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

    protected $fillable = [
        self::NAME,
        self::UNIQUE_NAME,
        self::PHONE,
        self::EMAIL,
        self::YOUTUBE,
        self::FACEBOOK,
        self::INSTAGRAM,
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

    public function user(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            UserChurch::tableName(),
            UserChurch::CHURCH_ID,
            UserChurch::USER_ID,
        );
    }

    public function imagesChurch(): BelongsToMany
    {
        return $this->belongsToMany(
            Image::class,
            ChurchImage::class,
            ChurchImage::CHURCH_ID,
            ChurchImage::IMAGE_ID,
        );
    }

    public function adminUser(): BelongsToMany
    {
        return $this->belongsToMany(
            AdminUser::class,
            ResponsibleChurch::class,
            ResponsibleChurch::CHURCH_ID,
            ResponsibleChurch::ADMIN_USER_ID,
        );
    }
}
