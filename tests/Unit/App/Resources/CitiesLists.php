<?php

namespace Tests\Unit\App\Resources;

use App\Features\City\Cities\Infra\Models\City;
use App\Features\City\States\Infra\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;

class CitiesLists
{
    public static function getCities(): Collection
    {
        return Collection::make([
            [
                City::ID => Uuid::uuid4()->toString(),
                City::DESCRIPTION => 'São Leopoldo',
                City::UF => 'RS',
            ],
            [
                City::ID => Uuid::uuid4()->toString(),
                City::DESCRIPTION => 'Novo Hamburgo',
                City::UF => 'RS',
            ]
        ]);
    }

    public static function showCityById(string $id = null): object
    {
        if(is_null($id)) {
            $id = Uuid::uuid4()->toString();
        }

        return (object) ([
            City::ID => $id,
            City::DESCRIPTION => 'São Leopoldo',
            City::UF => 'RS',
        ]);
    }

    public static function getStates(): Collection
    {
        return Collection::make([
            [
                State::ID => Uuid::uuid4()->toString(),
                State::DESCRIPTION => 'Rio Grande do Sul',
                State::UF => 'RS',
            ],
            [
                State::ID => Uuid::uuid4()->toString(),
                State::DESCRIPTION => 'Rio Grande do Norte',
                State::UF => 'RN',
            ]
        ]);
    }

    public static function showStateByUF(string $id = null): object
    {
        if(is_null($id)) {
            $id = Uuid::uuid4()->toString();
        }

        return (object) ([
            State::ID => $id,
            State::DESCRIPTION => 'Rio Grande do Sul',
            State::UF => 'RS',
        ]);
    }
}
