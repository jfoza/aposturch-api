<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAuthenticatedUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowCountAdminUsersByProfileInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\CreateAdminUserService;
use App\Features\Users\AdminUsers\Services\FindAllAdminUsersService;
use App\Features\Users\AdminUsers\Services\ShowAdminUserService;
use App\Features\Users\AdminUsers\Services\ShowCountAdminUsersByProfile;
use App\Features\Users\AdminUsers\Services\UpdateAdminUserService;
use App\Features\Users\Users\Services\ShowAuthenticatedUserService;

class AdminUsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ShowAuthenticatedUserServiceInterface::class => ShowAuthenticatedUserService::class,

        AdminUsersRepositoryInterface::class => AdminUsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllAdminUsersServiceInterface::class,
            FindAllAdminUsersService::class,
        );

        $this->bind(
            ShowAdminUserServiceInterface::class,
            ShowAdminUserService::class,
        );

        $this->bind(
            CreateAdminUserServiceInterface::class,
            CreateAdminUserService::class,
        );

        $this->bind(
            UpdateAdminUserServiceInterface::class,
            UpdateAdminUserService::class,
        );

        $this->bind(
            ShowCountAdminUsersByProfileInterface::class,
            ShowCountAdminUsersByProfile::class,
        );
    }
}
