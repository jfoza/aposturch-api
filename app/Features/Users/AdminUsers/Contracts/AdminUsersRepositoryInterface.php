<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Models\AdminUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AdminUsersRepositoryInterface
{
    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $userId, array $profiles): ?object;
    public function findByEmail(string $userEmail, array $profiles): ?object;
    public function create(string $userId): AdminUser;
}
