<?php

namespace App\Features\Users\AdminUsers\Traits;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use Illuminate\Database\Eloquent\Builder;

trait AdminUsersListTrait
{
    public function baseQueryBuilder(): Builder
    {
        return User::with(['adminUser', 'profile'])
            ->whereRelation(
                'adminUser',
                AdminUser::USER_ID,
                '!='
            );
    }

    public function baseQueryBuilderFilters(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this
            ->baseQueryBuilder()
            ->when(
                isset($adminUsersFiltersDTO->name),
                fn($q) => $q->where(
                    User::NAME,
                    'ilike',
                    "%{$adminUsersFiltersDTO->name}%"
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->email),
                fn($q) => $q->where(User::EMAIL, $adminUsersFiltersDTO->email)
            )
            ->when(
                isset($adminUsersFiltersDTO->profileUniqueName),
                fn($q) => $q->whereHas(
                    'profile',
                    fn($p) => $p->whereIn(
                        Profile::tableField(Profile::UNIQUE_NAME),
                        $adminUsersFiltersDTO->profileUniqueName
                    )
                )
            );
    }
}
