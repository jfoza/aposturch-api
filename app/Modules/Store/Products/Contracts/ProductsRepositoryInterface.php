<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use Illuminate\Support\Collection;

interface ProductsRepositoryInterface
{
    public function findAll(ProductsFiltersDTO $productsFiltersDTO);
    public function findAllByIds(array $productsId): Collection;
    public function findBySubcategory(string $subcategoryId): Collection;
}
