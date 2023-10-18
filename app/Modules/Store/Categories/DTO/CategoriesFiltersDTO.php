<?php

namespace App\Modules\Store\Categories\DTO;

use App\Base\DTO\FiltersDTO;

class CategoriesFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?bool $active;
    public ?bool $hasSubcategories;
}
