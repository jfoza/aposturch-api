<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;

interface AdminUsersRepositoryInterface
{
    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findById(string $id);
    public function findByAdminIdsAndProfile(array $adminIds, string $profileUniqueName);
    public function findByUserId(string $userId);
    public function findByUserIdAndProfileUniqueName(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findByEmail(string $email);
    public function create(string $userId);
}
