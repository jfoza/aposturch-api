<?php

namespace App\Features\Users\AdminUsers\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\AdminUsers\Traits\AdminUsersListTrait;
use App\Features\Users\Users\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminUsersRepository implements AdminUsersRepositoryInterface
{
    use AdminUsersListTrait;
    use BuilderTrait;

    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection
    {
         $builder = $this->baseQueryBuilderFilters($adminUsersFiltersDTO);

         return $this->paginateOrGet($builder, $adminUsersFiltersDTO->paginationOrder);
    }

    public function findById(string $userId, array $profiles): ?object
    {
        return $this
            ->baseQueryBuilder($profiles)
            ->where(
                User::ID,
                $userId
            )
            ->first();
    }

    public function findByEmail(string $userEmail, array $profiles): ?object
    {
        return $this
            ->baseQueryBuilder($profiles)
            ->where(
                User::EMAIL,
                $userEmail
            )
            ->first();
    }

    public function create(string $userId): AdminUser
    {
        return AdminUser::create([
            AdminUser::USER_ID => $userId
        ]);
    }
}
