<?php

namespace App\Features\Users\Users\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Users\Contracts\UpdateStatusUserServiceInterface;
use App\Features\Users\Users\Contracts\UserEmailAlreadyExistsServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\UpdateStatusUserService;
use App\Features\Users\Users\Services\UserEmailAlreadyExistsService;

class UsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            UpdateStatusUserServiceInterface::class,
            UpdateStatusUserService::class,
        );

        $this->bind(
            UserEmailAlreadyExistsServiceInterface::class,
            UserEmailAlreadyExistsService::class,
        );
    }
}
