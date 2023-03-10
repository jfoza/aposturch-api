<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\AdminUsersListBusiness;
use App\Features\Users\AdminUsers\Contracts\AdminUsersListBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;

class AdminUsersPersistenceBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        AdminUsersRepositoryInterface::class => AdminUsersRepository::class,
    ];

    public function getBusinessAbstract(): string
    {
        return AdminUsersListBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersListBusiness::class;
    }
}
