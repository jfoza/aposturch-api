<?php

namespace App\Features\Users\AdminUsers\Infra\Repositories;

use App\Features\Base\Http\Pagination\PaginationOrder;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Infra\Models\AdminUser;
use App\Features\Users\AdminUsers\Traits\AdminUsersListTrait;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Infra\Models\User;

class AdminUsersRepository implements AdminUsersRepositoryInterface
{
    use AdminUsersListTrait;

    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
         return $this
             ->baseQuery($adminUsersFiltersDTO)
             ->whereIn(
                 Profile::tableField(Profile::UNIQUE_NAME),
                 $adminUsersFiltersDTO->profileUniqueName
             )
             ->orderBy(
                 $this->defineColumnName($adminUsersFiltersDTO->paginationOrder),
                 $adminUsersFiltersDTO->paginationOrder->getColumnOrder(),
             )
             ->paginate($adminUsersFiltersDTO->paginationOrder->getPerPage());
    }

    public function findById(string $id): mixed
    {
        return AdminUser::where(AdminUser::ID, $id)->first();
    }

    public function findByUserId(string $userId)
    {
        return AdminUser::with(['user.profile'])
            ->whereRelation(
                'user',
                User::ID,
                '=',
                $userId
            )
            ->first();
    }

    public function findByUserIdAndProfileUniqueName(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this
            ->baseQuery($adminUsersFiltersDTO)
            ->where(
                User::tableField(User::ID),
                $adminUsersFiltersDTO->userId
            )
            ->whereIn(
                Profile::tableField(Profile::UNIQUE_NAME),
                $adminUsersFiltersDTO->profileUniqueName
            )
            ->first();
    }

    public function findByEmail(string $email)
    {
        return AdminUser::with([
            'user' => function($q) {
                return $q->with([
                    'profile',
                    'module',
                    'church'
                ]);
            }
        ])
        ->whereRelation(
            'user',
            User::EMAIL,
            '=',
            $email
        )
        ->first();
    }

    public function create(string $userId)
    {
        return AdminUser::create([
            AdminUser::USER_ID => $userId
        ]);
    }

    private function defineColumnName(PaginationOrder $paginationOrder): string
    {
        if($paginationOrder->getColumnName() == User::CREATED_AT) {
            return User::tableField($paginationOrder->getColumnName());
        }

        return $paginationOrder->getColumnName();
    }
}
