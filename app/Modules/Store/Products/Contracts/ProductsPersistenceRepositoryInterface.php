<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsDTO;

interface ProductsPersistenceRepositoryInterface
{
    public function create(ProductsDTO $productsDTO): object;
    public function save(ProductsDTO $productsDTO): object;
    public function saveSubcategories(string $productId, array $subcategoriesId): void;
    public function saveStatus(string $id, bool $status): object;
}
