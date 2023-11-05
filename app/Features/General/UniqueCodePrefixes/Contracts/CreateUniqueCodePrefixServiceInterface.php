<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;

interface CreateUniqueCodePrefixServiceInterface
{
    public function execute(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object;
}
