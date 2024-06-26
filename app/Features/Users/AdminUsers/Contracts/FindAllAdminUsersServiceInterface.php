<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllAdminUsersServiceInterface
{
    public function execute(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection;
}
