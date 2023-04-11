<?php

namespace App\Features\ZipCode\Contracts;

interface ZipCodeBusinessInterface
{
    public function findByZipCode(string $zipCode);
}
