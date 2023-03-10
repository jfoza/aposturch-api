<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\Factories\AdminUsersListFactory;
use App\Features\Users\AdminUsers\Contracts\AdminUsersListFactoryInterface;

class AdminUsersListFactoryProvider extends ServiceProviderAbstract
{
    public array $bindings = [];

    public function getBusinessAbstract(): string
    {
        return AdminUsersListFactoryInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersListFactory::class;
    }
}
