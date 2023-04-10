<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Shared\ACL\Policy;

interface ShowAdminUserServiceInterface
{
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO, Policy $policy): mixed;
}
