<?php

namespace App\Features\City\States\Models;


use App\Features\Base\Infra\Models\Register;

class State extends Register
{
    const ID = 'id';
    const UF = 'uf';
    const DESCRIPTION = 'description';

    protected $table = 'city.state';

    protected $primaryKey = self::ID;

    protected $keyType = 'string';

    protected $fillable = [
        self::UF,
        self::DESCRIPTION
    ];
}
