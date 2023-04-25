<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;

interface ShowAdminUserServiceInterface
{
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO): mixed;
}
