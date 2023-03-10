<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\AdminUsersPersistenceBusiness;
use App\Features\Users\AdminUsers\Contracts\AdminUsersPersistenceBusinessInterface;

class AdminUsersListBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [];

    public function getBusinessAbstract(): string
    {
        return AdminUsersPersistenceBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersPersistenceBusiness::class;
    }
}
