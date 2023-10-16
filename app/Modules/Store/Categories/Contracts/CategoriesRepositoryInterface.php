<?php

namespace App\Modules\Store\Categories\Contracts;

use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface CategoriesRepositoryInterface
{
    public function findAll(CategoriesFiltersDTO $categoriesFiltersDTO): LengthAwarePaginator|Collection;
    public function findAllByIds(array $categoriesId): Collection;
    public function findById(string $id): ?object;
    public function findByName(string $name): ?object;
    public function create(CategoriesDTO $categoriesDTO): object;
    public function save(CategoriesDTO $categoriesDTO): object;
    public function updateStatus(string $id, bool $status): object;
    public function remove(string $id): void;
}
