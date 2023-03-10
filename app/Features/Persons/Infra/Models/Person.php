<?php

namespace App\Features\Persons\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\City\Cities\Infra\Models\City;
use App\Features\Users\Users\Infra\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Person extends Register
{
    use HasFactory;

    const ID             = 'id';
    const CITY_ID        = 'city_id';
    const PHONE          = 'phone';
    const ZIP_CODE       = 'zip_code';
    const ADDRESS        = 'address';
    const NUMBER_ADDRESS = 'number_address';
    const COMPLEMENT     = 'complement';
    const DISTRICT       = 'district';
    const UF             = 'uf';

    protected $table = 'person.persons';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::CITY_ID,
        self::PHONE,
        self::ZIP_CODE,
        self::ADDRESS,
        self::NUMBER_ADDRESS,
        self::COMPLEMENT,
        self::DISTRICT,
        self::UF,
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, User::PERSON_ID, self::ID);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, self::CITY_ID, City::ID);
    }
}
