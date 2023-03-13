<?php

namespace App\Features\Users\AdminUsers\Business;

use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Http\Responses\LoggedUserResponse;
use App\Features\Users\Users\DTO\UserDTO;

class AdminUsersBusiness extends Business implements AdminUsersBusinessInterface
{
    public function __construct(
        private readonly FindAllAdminUsersServiceInterface $adminUsersListingService,
        private readonly ShowAdminUserServiceInterface $showAdminUserService,
        private readonly ShowLoggedUserServiceInterface $showLoggedUserService,
        private readonly CreateAdminUserServiceInterface $createAdminUserService,
        private readonly UpdateAdminUserServiceInterface $updateAdminUserService,
    ) {}

    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this->adminUsersListingService->execute(
            $adminUsersFiltersDTO,
            $this->getPolicy()
        );
    }

    public function findByUserId(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this->showAdminUserService->execute(
            $adminUsersFiltersDTO,
            $this->getPolicy()
        );
    }

    public function findLoggedUser(): LoggedUserResponse
    {
        return $this->showLoggedUserService->execute();
    }

    public function create(UserDTO $userDTO): AdminUserResponse
    {
        return $this->createAdminUserService->execute(
            $userDTO,
            $this->getPolicy()
        );
    }

    public function save(UserDTO $userDTO): AdminUserResponse
    {
        return $this->updateAdminUserService->execute(
            $userDTO,
            $this->getPolicy()
        );
    }
}
