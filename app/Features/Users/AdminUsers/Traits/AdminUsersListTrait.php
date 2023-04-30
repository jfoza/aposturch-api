<?php

namespace App\Features\Users\AdminUsers\Traits;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Models\AdminUser;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\ProfilesUsers\Infra\Models\ProfileUser;
use App\Features\Users\Users\Models\User;

trait AdminUsersListTrait
{
    public function baseQuery()
    {
        return AdminUser::select(
                AdminUser::tableField(AdminUser::ID). ' AS admin_user_id',
                User::tableField(User::ID).' AS user_id',
                User::tableField(User::NAME).' AS user_name',
                User::tableField(User::EMAIL).' AS user_email',
                User::tableField(User::ACTIVE).' AS user_active',
                User::tableField(User::CREATED_AT).' AS user_created_at',
                Profile::tableField(Profile::ID).' AS profile_id',
                Profile::tableField(Profile::UNIQUE_NAME).' AS profile_unique_name',
                Profile::tableField(Profile::DESCRIPTION).' AS profile_description',
            )
            ->leftJoin(
                User::tableName(),
                AdminUser::tableField(AdminUser::USER_ID),
                User::tableField(User::ID),
            )
            ->leftJoin(
                ProfileUser::tableName(),
                User::tableField(User::ID),
                ProfileUser::tableField(ProfileUser::USER_ID),
            )
            ->leftJoin(
                Profile::tableName(),
                ProfileUser::tableField(ProfileUser::PROFILE_ID),
                Profile::tableField(Profile::ID),
            );
    }

    public function baseQueryFilters(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this
            ->baseQuery()
            ->when(
                isset($adminUsersFiltersDTO->adminsId),
                fn($q) => $q->whereIn(
                    AdminUser::tableField(AdminUser::ID),
                    $adminUsersFiltersDTO->adminsId
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->email),
                fn($q) => $q->where(
                    User::tableField(User::EMAIL),
                    $adminUsersFiltersDTO->email
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->profileId),
                fn($q) => $q->where(
                    Profile::tableField(Profile::ID),
                    $adminUsersFiltersDTO->profileId
                )
            )
            ->when(
                isset($adminUsersFiltersDTO->name),
                fn($q) => $q->where(
                    User::tableField(User::NAME),
                    'ilike',
                    "%{$adminUsersFiltersDTO->name}%"
                )
            );
    }
}
