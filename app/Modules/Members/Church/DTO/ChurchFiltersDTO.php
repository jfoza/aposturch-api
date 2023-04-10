<?php

namespace App\Modules\Members\Church\DTO;

use App\Features\Base\DTO\FiltersDTO;

class ChurchFiltersDTO extends FiltersDTO
{
    public string|null $name;
    public string|null $cityId;
}
