<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\Factories\AdminUsersInsertFactory;
use App\Features\Users\AdminUsers\Contracts\AdminUsersInsertFactoryInterface;

class AdminUsersInsertFactoryProvider extends ServiceProviderAbstract
{
    public function getBusinessAbstract(): string
    {
        return AdminUsersInsertFactoryInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersInsertFactory::class;
    }
}
