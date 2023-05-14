<?php

namespace App\Modules\Membership\Members\Contracts;

use App\Modules\Membership\Members\DTO\MemberDTO;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface MembersRepositoryInterface
{
    public function findAll(MembersFiltersDTO $membersFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): ?object;
    public function findByUserId(string $id): ?object;
    public function findByIds(array $ids): Collection;
    public function create(MemberDTO $memberDTO): object;
}
