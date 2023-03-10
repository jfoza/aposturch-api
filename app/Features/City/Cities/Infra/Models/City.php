<?php

namespace App\Features\City\Cities\Infra\Models;

use App\Features\Base\Infra\Models\Register;
use App\Features\Persons\Infra\Models\Person;

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

    public function person() {
        return $this->hasMany(Person::class, Person::CITY_ID, self::ID);
    }
}
