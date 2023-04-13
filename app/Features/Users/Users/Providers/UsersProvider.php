<?php

namespace App\Features\Users\Users\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Users\Contracts\FindAllUsersServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Infra\Repositories\UsersRepository;
use App\Features\Users\Users\Services\FindAllUsersService;

class UsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllUsersServiceInterface::class,
            FindAllUsersService::class,
        );
    }
}
