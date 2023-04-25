<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\Users\Users\DTO\UserFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindUsersByChurchServiceInterface
{
    public function execute(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|Collection;
}
