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
         $builder = $this
             ->baseQueryBuilderFilters($adminUsersFiltersDTO)
             ->orderBy(
                 $adminUsersFiltersDTO->paginationOrder->defineCustomColumnName(User::CREATED_AT),
                 $adminUsersFiltersDTO->paginationOrder->getColumnOrder(),
             );

         return $this->paginateOrGet($builder, $adminUsersFiltersDTO->paginationOrder);
    }

    public function findByUserId(string $userId): ?object
    {
        return $this
            ->baseQueryBuilder()
            ->where(
                User::ID,
                $userId
            )
            ->first();
    }

    public function findByUserEmail(string $userEmail): ?object
    {
        return $this
            ->baseQueryBuilder()
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
