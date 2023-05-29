<?php

namespace App\Modules\Membership\Church\DTO;

use App\Features\Base\DTO\FiltersDTO;

class ChurchFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $cityId;
    public ?array $churchIds;
}
