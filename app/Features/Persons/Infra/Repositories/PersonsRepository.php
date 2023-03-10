<?php

namespace App\Features\Persons\Infra\Repositories;

use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\DTO\PersonDTO;
use App\Features\Persons\Infra\Models\Person;

class PersonsRepository implements PersonsRepositoryInterface
{
    public function create(PersonDTO $personDTO)
    {
        return Person::create([
            Person::CITY_ID        => $personDTO->cityId,
            Person::PHONE          => $personDTO->phone,
            Person::ZIP_CODE       => $personDTO->zipCode,
            Person::ADDRESS        => $personDTO->address,
            Person::NUMBER_ADDRESS => $personDTO->numberAddress,
            Person::COMPLEMENT     => $personDTO->complement,
            Person::DISTRICT       => $personDTO->district,
            Person::UF             => $personDTO->uf,
        ]);
    }

    public function save(PersonDTO $personDTO)
    {
        $saved = [
            Person::ID             => $personDTO->id,
            Person::CITY_ID        => $personDTO->cityId,
            Person::PHONE          => $personDTO->phone,
            Person::ZIP_CODE       => $personDTO->zipCode,
            Person::ADDRESS        => $personDTO->address,
            Person::NUMBER_ADDRESS => $personDTO->numberAddress,
            Person::COMPLEMENT     => $personDTO->complement,
            Person::DISTRICT       => $personDTO->district,
            Person::UF             => $personDTO->uf,
        ];

        Person::where(Person::ID, $personDTO->id)
            ->update($saved);

        return (object) $saved;
    }
}
