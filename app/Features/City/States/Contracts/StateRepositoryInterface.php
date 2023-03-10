<?php
namespace App\Features\City\States\Contracts;

interface StateRepositoryInterface
{
    public function findAll();
    public function findById(string $id);
    public function findByUF(string $uf);
}
