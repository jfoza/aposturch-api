<?php

namespace App\Features\Users\AdminUsers\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\AdminUsers\Business\AdminUsersBusiness;
use App\Features\Users\AdminUsers\Contracts\AdminUsersBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Infra\Repositories\AdminUsersRepository;
use App\Features\Users\AdminUsers\Services\CreateAdminUserService;
use App\Features\Users\AdminUsers\Services\FindAllAdminUsersService;
use App\Features\Users\AdminUsers\Services\ShowAdminUserService;
use App\Features\Users\AdminUsers\Services\ShowLoggedUserService;
use App\Features\Users\AdminUsers\Services\UpdateAdminUserService;

class AdminUsersBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        FindAllAdminUsersServiceInterface::class => FindAllAdminUsersService::class,
        ShowAdminUserServiceInterface::class     => ShowAdminUserService::class,
        CreateAdminUserServiceInterface::class   => CreateAdminUserService::class,
        UpdateAdminUserServiceInterface::class   => UpdateAdminUserService::class,
        ShowLoggedUserServiceInterface::class    => ShowLoggedUserService::class,
        AdminUsersRepositoryInterface::class     => AdminUsersRepository::class,
    ];
    public function getBusinessAbstract(): string
    {
        return AdminUsersBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return AdminUsersBusiness::class;
    }
}
