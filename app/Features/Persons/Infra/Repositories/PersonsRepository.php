<?php

namespace App\Features\Persons\Infra\Repositories;

use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\DTO\PersonDTO;
use App\Features\Persons\Infra\Models\Person;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;

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

    public function savePhone(string $personId, string $phone): object
    {
        $saved = [
            Person::ID    => $personId,
            Person::PHONE => $phone
        ];

        Person::where(Person::ID, $personId)->update($saved);

        return (object) $saved;
    }

    public function saveAddress(string $personId, AddressDataUpdateDTO $addressDataUpdateDTO): object
    {
        $saved = [
            Person::ID             => $personId,
            Person::ZIP_CODE       => $addressDataUpdateDTO->zipCode,
            Person::ADDRESS        => $addressDataUpdateDTO->address,
            Person::NUMBER_ADDRESS => $addressDataUpdateDTO->numberAddress,
            Person::COMPLEMENT     => $addressDataUpdateDTO->complement,
            Person::DISTRICT       => $addressDataUpdateDTO->district,
            Person::CITY_ID        => $addressDataUpdateDTO->cityId,
            Person::UF             => $addressDataUpdateDTO->uf,
        ];

        Person::where(Person::ID, $personId)->update($saved);

        return (object) $saved;
    }

    public function saveAll(PersonDTO $personDTO): object
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

        Person::where(Person::ID, $personDTO->id)->update($saved);

        return (object) $saved;
    }
}
