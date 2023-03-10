<?php
namespace App\Features\City\Cities\Contracts;

interface CityBusinessInterface
{
    public function findByUF(string $uf);
    public function findById(string $id);
    public function findAllInPersons();
}
