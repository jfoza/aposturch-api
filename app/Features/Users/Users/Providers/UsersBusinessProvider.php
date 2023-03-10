<?php

namespace App\Features\Users\Users\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\Users\Business\UsersBusiness;
use App\Features\Users\Users\Contracts\UsersBusinessInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Infra\Repositories\UsersRepository;

class UsersBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];

    public function getBusinessAbstract(): string
    {
        return UsersBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return UsersBusiness::class;
    }
}
