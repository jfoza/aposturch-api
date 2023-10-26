<?php

namespace App\Modules\Store\Products\DTO;

use App\Base\DTO\FiltersDTO;

class ProductsFiltersDTO extends FiltersDTO
{
    public ?string $name;
    public ?array  $subcategoriesId;
    public ?string $code;
    public ?bool   $highlight;
    public ?bool   $active;
}
