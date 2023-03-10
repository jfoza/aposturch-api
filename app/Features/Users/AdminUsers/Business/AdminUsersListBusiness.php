<?php

namespace App\Features\Users\AdminUsers\Business;

use App\Shared\Utils\Auth;
use App\Features\Auth\Http\Responses\Admin\AdminUserResponse;
use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersListBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersListFactoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;

class AdminUsersListBusiness
    extends Business
    implements AdminUsersListBusinessInterface
{
    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly RulesRepositoryInterface $rulesRepository,
        private readonly AdminUserResponse $adminUserResponse,
        private readonly AdminUsersListFactoryInterface $adminUsersListFactory,
    ) {}

    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this->adminUsersListFactory->findAllByProfileRule($adminUsersFiltersDTO);
    }

    public function findByUserId(AdminUsersFiltersDTO $adminUsersFiltersDTO)
    {
        return $this->adminUsersListFactory->showByProfileRule($adminUsersFiltersDTO);
    }

    public function findLoggedUser(bool $resource = false): AdminUserResponse
    {
        $loggedUser = Auth::getUser();

        $adminUser = $this->adminUsersRepository->findByEmail($loggedUser->email);

        if($resource) {
            $this->adminUserResponse->id       = $adminUser->user->id;
            $this->adminUserResponse->email    = $adminUser->user->email;
            $this->adminUserResponse->avatar   = $adminUser->user->avatar;
            $this->adminUserResponse->fullName = $adminUser->user->name;
            $this->adminUserResponse->role     = $adminUser->user->profile;
            $this->adminUserResponse->status   = $adminUser->user->active;
            $this->adminUserResponse->ability  = $this->rulesRepository->findAllByUserId($adminUser->user->id);

            return $this->adminUserResponse;
        }

        return $adminUser->user;
    }
}
