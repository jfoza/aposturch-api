<?php

namespace App\Modules\Store\Subcategories\Contracts;

use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllSubcategoriesServiceInterface
{
    public function execute(SubcategoriesFiltersDTO $subcategoriesFiltersDTO): LengthAwarePaginator|Collection;
}
