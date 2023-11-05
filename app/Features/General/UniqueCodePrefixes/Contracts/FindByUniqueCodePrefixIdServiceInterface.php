<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

interface FindByUniqueCodePrefixIdServiceInterface
{
    public function execute(string $id): object;
}
