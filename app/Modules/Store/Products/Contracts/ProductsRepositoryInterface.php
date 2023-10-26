<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductsRepositoryInterface
{
    public function findAll(ProductsFiltersDTO $productsFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): ?object;
    public function findByName(string $productName): ?object;
    public function findByUniqueName(string $productUniqueName): ?object;
    public function findByCode(string $code): ?object;
    public function findAllByIds(array $productsId): Collection;
    public function findBySubcategory(string $subcategoryId): Collection;
}
