<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowCountAdminUsersByProfileInterface;
use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\CreateAdminUserAuthenticatedService;
use App\Features\Users\AdminUsers\Services\FindAllAdminUsersAuthenticatedService;
use App\Features\Users\AdminUsers\Services\ShowAdminUserAuthenticatedService;
use App\Features\Users\AdminUsers\Services\ShowCountAdminUsersByProfile;
use App\Features\Users\AdminUsers\Services\UpdateAdminUserAuthenticatedService;
use App\Features\Users\Users\Services\ShowLoggedUserService;

class AdminUsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ShowLoggedUserServiceInterface::class => ShowLoggedUserService::class,

        AdminUsersRepositoryInterface::class => AdminUsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllAdminUsersServiceInterface::class,
            FindAllAdminUsersAuthenticatedService::class,
        );

        $this->bind(
            ShowAdminUserServiceInterface::class,
            ShowAdminUserAuthenticatedService::class,
        );

        $this->bind(
            CreateAdminUserServiceInterface::class,
            CreateAdminUserAuthenticatedService::class,
        );

        $this->bind(
            UpdateAdminUserServiceInterface::class,
            UpdateAdminUserAuthenticatedService::class,
        );

        $this->bind(
            ShowCountAdminUsersByProfileInterface::class,
            ShowCountAdminUsersByProfile::class,
        );
    }
}
