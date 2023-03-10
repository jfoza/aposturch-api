<?php

namespace App\Features\Persons\Contracts;

use App\Features\Persons\DTO\PersonDTO;

interface PersonsRepositoryInterface
{
    public function create(PersonDTO $personDTO);
    public function save(PersonDTO $personDTO);
}
