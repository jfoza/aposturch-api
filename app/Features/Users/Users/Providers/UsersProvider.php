<?php

namespace App\Features\Users\Users\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Users\Contracts\FindUsersByChurchServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\FindUsersByChurchService;

class UsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindUsersByChurchServiceInterface::class,
            FindUsersByChurchService::class,
        );
    }
}
