<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Symfony\Component\HttpFoundation\Response;

class ShowAdminUserService implements ShowAdminUserServiceInterface
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

        $adminUser = match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value)     => $this->findByEmployee(),
        };

        if(empty($adminUser)) {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $adminUser;
    }

    private function findByAdminMaster()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::EMPLOYEE,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }

    private function findByEmployee()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::EMPLOYEE,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }
}
