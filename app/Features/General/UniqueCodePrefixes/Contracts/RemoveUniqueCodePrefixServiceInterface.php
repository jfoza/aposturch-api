<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use Illuminate\Support\Collection;

interface RemoveUniqueCodePrefixServiceInterface
{
    public function execute(string $id): void;
}
