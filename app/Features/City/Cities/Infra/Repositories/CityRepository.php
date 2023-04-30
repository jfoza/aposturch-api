<?php

namespace App\Features\City\Cities\Infra\Repositories;

use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Infra\Models\City;
use App\Features\Persons\Infra\Models\Person;
use App\Modules\Membership\Church\Models\Church;

class CityRepository implements CityRepositoryInterface
{
    public function findByUF(string $uf)
    {
        return City::select(
                City::tableField(City::ID),
                City::tableField(City::DESCRIPTION),
                City::tableField(City::UF),
            )
            ->where(City::UF, $uf)
            ->orderBy(City::tableField(City::DESCRIPTION), 'ASC')
            ->get();
    }

    public function findById(string $id)
    {
        return City::select(
                City::tableField(City::ID),
                City::tableField(City::DESCRIPTION),
                City::tableField(City::UF),
            )
            ->where(City::ID, $id)->first();
    }

    public function findByDescription(string $description, string $uf)
    {
        return City::select(
                City::tableField(City::ID),
                City::tableField(City::DESCRIPTION),
                City::tableField(City::UF),
            )
            ->where([
                City::DESCRIPTION => $description,
                City::UF => $uf
            ])
            ->first();
    }

    public function findAllInPersons()
    {
        return City::select(
                City::tableField(City::ID),
                City::tableField(City::DESCRIPTION),
                City::tableField(City::UF),
            )
            ->join(
                Person::tableName(),
                Person::tableField(Person::CITY_ID),
                City::tableField(City::ID)
            )
            ->groupBy(City::tableField(City::ID))
            ->orderBy(City::tableField(City::DESCRIPTION), 'ASC')
            ->get();
    }

    public function findAllInChurches()
    {
        return City::select(
                City::tableField(City::ID),
                City::tableField(City::DESCRIPTION),
                City::tableField(City::UF),
            )
            ->join(
                Church::tableName(),
                Church::tableField(Church::CITY_ID),
                City::tableField(City::ID)
            )
            ->groupBy(City::tableField(City::ID))
            ->orderBy(City::tableField(City::DESCRIPTION), 'ASC')
            ->get();
    }
}
