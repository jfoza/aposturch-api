<?php

namespace App\Features\General\UniqueCodePrefixes\DTO;

use App\Base\DTO\FiltersDTO;

class UniqueCodePrefixesFiltersDTO extends FiltersDTO
{
    public ?string $prefix;
}
