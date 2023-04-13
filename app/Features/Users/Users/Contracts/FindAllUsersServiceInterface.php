<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\Users\Users\DTO\UserFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllUsersServiceInterface
{
    public function execute(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|Collection;
}
