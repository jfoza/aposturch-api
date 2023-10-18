<?php

namespace App\Modules\Store\Products\Contracts;

use Illuminate\Support\Collection;

interface ProductsRepositoryInterface
{
    public function findAllByIds(array $productsId): Collection;
    public function findBySubcategory(string $subcategoryId): Collection;
}
