<?php
namespace App\Features\City\States\Contracts;

interface StateBusinessInterface
{
    public function findAll();
    public function findById(string $id);
    public function findByUF(string $uf);
}
