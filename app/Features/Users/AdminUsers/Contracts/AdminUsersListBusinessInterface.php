<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;

interface AdminUsersListBusinessInterface
{
    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findByUserId(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findLoggedUser(bool $resource = false);
}
