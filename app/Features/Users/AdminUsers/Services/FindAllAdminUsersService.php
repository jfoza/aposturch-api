<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;

class FindAllAdminUsersService implements FindAllAdminUsersServiceInterface
{
    private AdminUsersFiltersDTO $adminUsersFiltersDTO;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(
        AdminUsersFiltersDTO $adminUsersFiltersDTO,
        Policy $policy
    ): mixed
    {
        $this->adminUsersFiltersDTO = $adminUsersFiltersDTO;

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value)     => $this->findByEmployee(),
            default                                                            => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::EMPLOYEE,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByEmployee()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::EMPLOYEE,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }
}
