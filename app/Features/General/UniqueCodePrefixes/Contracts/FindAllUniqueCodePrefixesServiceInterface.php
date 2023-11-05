<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllUniqueCodePrefixesServiceInterface
{
    public function execute(UniqueCodePrefixesFiltersDTO $uniqueCodePrefixesFiltersDTO): LengthAwarePaginator|Collection;
}
