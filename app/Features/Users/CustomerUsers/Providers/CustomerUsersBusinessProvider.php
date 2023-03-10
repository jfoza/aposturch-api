<?php

namespace App\Features\Users\CustomerUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\CustomerUsers\Business\CustomerUsersBusiness;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersBusinessInterface;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\Infra\Repositories\CustomerUsersRepository;

class CustomerUsersBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        CustomerUsersRepositoryInterface::class => CustomerUsersRepository::class,
    ];

    public function getBusinessAbstract(): string
    {
        return CustomerUsersBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return CustomerUsersBusiness::class;
    }
}
