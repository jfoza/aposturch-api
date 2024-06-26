<?php

namespace App\Modules\Store\Categories\DTO;

use App\Base\DTO\FiltersDTO;

class CategoriesFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $departmentId;
    public ?bool $active;
    public ?bool $hasProducts;
}
