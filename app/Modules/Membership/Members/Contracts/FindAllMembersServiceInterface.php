<?php

namespace App\Modules\Membership\Members\Contracts;

use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllMembersServiceInterface
{
    public function execute(MembersFiltersDTO $membersFiltersDTO): LengthAwarePaginator|Collection;
}
