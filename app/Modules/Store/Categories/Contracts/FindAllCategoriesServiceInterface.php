<?php

namespace App\Modules\Store\Categories\Contracts;

use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllCategoriesServiceInterface
{
    public function execute(CategoriesFiltersDTO $categoriesFiltersDTO): LengthAwarePaginator|Collection;
}
