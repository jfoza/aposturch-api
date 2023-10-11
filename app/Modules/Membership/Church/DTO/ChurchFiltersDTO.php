<?php

namespace App\Modules\Membership\Church\DTO;

use App\Base\DTO\FiltersDTO;

class ChurchFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $cityId;
    public ?bool $active;
    public ?array $churchIds;
}
