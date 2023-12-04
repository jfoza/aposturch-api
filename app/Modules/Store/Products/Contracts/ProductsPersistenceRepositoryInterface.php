<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsDTO;

interface ProductsPersistenceRepositoryInterface
{
    public function create(ProductsDTO $productsDTO): object;
    public function save(ProductsDTO $productsDTO): object;
    public function saveCategories(string $productId, array $categoriesId): void;
    public function saveImages(string $productId, array $imagesId): void;
    public function saveStatus(string $id, bool $status): object;
}
