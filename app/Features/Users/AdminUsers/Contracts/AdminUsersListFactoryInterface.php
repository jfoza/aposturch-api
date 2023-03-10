<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;

interface AdminUsersListFactoryInterface
{
    public function findAllByProfileRule(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function showByProfileRule(AdminUsersFiltersDTO $adminUsersFiltersDTO);
}
