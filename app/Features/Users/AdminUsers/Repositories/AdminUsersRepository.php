<?php

namespace App\Features\Users\AdminUsers\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\AdminUsers\Traits\AdminUsersListTrait;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\Users\Infra\Models\User;
use App\Modules\Members\Church\Models\Church;
use App\Modules\Members\ResponsibleChurch\Models\ResponsibleChurch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AdminUsersRepository implements AdminUsersRepositoryInterface
{
    use AdminUsersListTrait;
    use BuilderTrait;

    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO): LengthAwarePaginator|Collection
    {
         $builder = $this
             ->baseQueryFilters($adminUsersFiltersDTO)
             ->whereIn(
                 Profile::tableField(Profile::UNIQUE_NAME),
                 $adminUsersFiltersDTO->profileUniqueName
             )
             ->orderBy(
                 $adminUsersFiltersDTO->paginationOrder->defineCustomColumnName(User::tableField(User::CREATED_AT)),
                 $adminUsersFiltersDTO->paginationOrder->getColumnOrder(),
             );

         return $this->paginateOrGet($builder, $adminUsersFiltersDTO->paginationOrder);
    }

    public function findAllResponsibleChurch(string $churchId): mixed
    {
        return $this
            ->baseQuery()
            ->leftJoin(
                ResponsibleChurch::tableName(),
                ResponsibleChurch::tableField(ResponsibleChurch::ADMIN_USER_ID),
                AdminUser::tableField(AdminUser::ID)
            )
            ->leftJoin(
                Church::tableName(),
                Church::tableField(Church::ID),
                ResponsibleChurch::tableField(ResponsibleChurch::CHURCH_ID)
            )
            ->where(
                Church::tableField(Church::ID),
                $churchId
            )
            ->get();
    }

    public function findById(string $id): ?object
    {
        return AdminUser::where(AdminUser::ID, $id)->first();
    }

    public function findOneByFilters(AdminUsersFiltersDTO $adminUsersFiltersDTO): mixed
    {
        return $this
            ->baseQuery()
            ->when(
                isset($adminUsersFiltersDTO->userId),
                fn($q) => $q->where(
                    User::tableField(User::ID),
                    $adminUsersFiltersDTO->userId
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->adminsId),
                fn($q) => $q->whereIn(
                    AdminUser::tableField(AdminUser::ID),
                    $adminUsersFiltersDTO->adminsId
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->profileUniqueName),
                fn($q) => $q->whereIn(
                    Profile::tableField(Profile::UNIQUE_NAME),
                    $adminUsersFiltersDTO->profileUniqueName
                )
            )
            ->first();
    }

    public function findByUserId(string $userId): ?object
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

    public function findByEmail(string $email): ?object
    {
        return AdminUser::with([
            'responsibleChurch',
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

    public function create(string $userId): mixed
    {
        return AdminUser::create([
            AdminUser::USER_ID => $userId
        ]);
    }
}
