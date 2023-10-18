<?php

namespace App\Modules\Store\Subcategories\DTO;

use App\Base\DTO\FiltersDTO;

class SubcategoriesFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?string $categoryId;
    public ?bool $active;
    public ?bool $hasProducts;
}
