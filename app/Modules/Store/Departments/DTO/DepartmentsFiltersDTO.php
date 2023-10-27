<?php

namespace App\Modules\Store\Departments\DTO;

use App\Base\DTO\FiltersDTO;

class DepartmentsFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?bool $active;
    public ?bool $hasCategories;
}
