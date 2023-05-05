<?php

namespace App\Modules\Membership\Church\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\City\Cities\Infra\Models\City;
use App\Features\General\Images\Models\Image;
use App\Modules\Membership\ChurchesImages\Models\ChurchImage;
use App\Modules\Membership\ChurchesMembers\Models\ChurchMember;
use App\Modules\Membership\Members\Models\Member;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Church extends Register
{
    use HasFactory;

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

    protected $table = 'membership.churches';

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

    public function member(): BelongsToMany
    {
        return $this->belongsToMany(
            Member::class,
            ChurchMember::tableName(),
            ChurchMember::CHURCH_ID,
            ChurchMember::MEMBER_ID,
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
}
