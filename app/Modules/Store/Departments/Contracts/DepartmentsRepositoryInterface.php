<?php

namespace App\Modules\Store\Departments\Contracts;

use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface DepartmentsRepositoryInterface
{
    public function findAll(DepartmentsFiltersDTO $departmentsFiltersDTO): LengthAwarePaginator|Collection;
    public function findAllByIds(array $departmentsId): Collection;
    public function findById(string $id): ?object;
    public function findByName(string $name): ?object;
    public function create(DepartmentsDTO $departmentsDTO): object;
    public function save(DepartmentsDTO $departmentsDTO): object;
    public function updateStatus(string $id, bool $status): object;
    public function remove(string $id): void;
}
