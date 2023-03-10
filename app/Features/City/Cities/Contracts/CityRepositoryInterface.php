<?php
namespace App\Features\City\Cities\Contracts;

interface CityRepositoryInterface
{
    public function findByUF(string $uf);
    public function findById(string $id);
    public function findByDescription(string $description, string $uf);
    public function findAllInPersons();
}
