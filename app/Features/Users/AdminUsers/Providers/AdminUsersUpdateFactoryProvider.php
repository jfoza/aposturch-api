<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\Factories\AdminUsersUpdateFactory;
use App\Features\Users\AdminUsers\Contracts\AdminUsersUpdateFactoryInterface;

class AdminUsersUpdateFactoryProvider extends ServiceProviderAbstract
{
    public function getBusinessAbstract(): string
    {
        return AdminUsersUpdateFactoryInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersUpdateFactory::class;
    }
}
