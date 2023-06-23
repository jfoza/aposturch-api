<?php

namespace App\Features\Persons\Contracts;

use App\Features\Persons\DTO\PersonDTO;
use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;

interface PersonsRepositoryInterface
{
    public function create(PersonDTO $personDTO);
    public function savePhone(string $personId, string $phone): object;
    public function saveAddress(string $personId, AddressDataUpdateDTO $addressDataUpdateDTO): object;
    public function saveAll(PersonDTO $personDTO): object;
}
