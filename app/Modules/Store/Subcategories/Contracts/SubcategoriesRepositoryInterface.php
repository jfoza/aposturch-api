<?php

namespace App\Modules\Store\Subcategories\Contracts;

use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface SubcategoriesRepositoryInterface
{
    public function findAll(SubcategoriesFiltersDTO $subcategoriesFiltersDTO): LengthAwarePaginator|Collection;
    public function findAllByIds(array $subcategoriesId): Collection;
    public function findById(string $id): ?object;
    public function findByName(string $name): ?object;
    public function findByCategory(string $categoryId): Collection;
    public function create(SubcategoriesDTO $subcategoriesDTO): object;
    public function save(SubcategoriesDTO $subcategoriesDTO): object;
    public function saveCategory(string $categoryId, array $subcategoriesId): void;
    public function remove(string $id): void;
}
