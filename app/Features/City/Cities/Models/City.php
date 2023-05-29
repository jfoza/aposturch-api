<?php

namespace App\Features\City\Cities\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Persons\Infra\Models\Person;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Register
{
    const ID          = 'id';
    const DESCRIPTION = 'description';
    const UF          = 'uf';
    const ACTIVE      = 'active';

    protected $table = 'city.cities';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    public $hidden = ['pivot'];

    protected $fillable = [
        self::DESCRIPTION,
        self::UF,
        self::ACTIVE,
    ];

    public function person(): HasMany
    {
        return $this->hasMany(Person::class, Person::CITY_ID, self::ID);
    }

    public function church(): HasMany {
        return $this->hasMany(Church::class, Church::CITY_ID, self::ID);
    }
}
