<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AdminUsersRepositoryInterface
{
    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection;
    public function findAllResponsibleChurch(string $churchId): mixed;
    public function findById(string $id): ?object;
    public function findOneByFilters(AdminUsersFiltersDTO $adminUsersFiltersDTO): mixed;
    public function findByUserId(string $userId): ?object;
    public function findByEmail(string $email): ?object;
    public function create(string $userId): mixed;
}
