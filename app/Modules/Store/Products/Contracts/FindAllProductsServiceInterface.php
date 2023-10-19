<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllProductsServiceInterface
{
    public function execute(ProductsFiltersDTO $productsFiltersDTO): LengthAwarePaginator|Collection;
}
