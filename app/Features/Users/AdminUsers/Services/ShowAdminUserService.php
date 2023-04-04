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
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value)     => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value)     => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_DEPARTMENT_VIEW->value) => $this->findByAdminDepartment(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ASSISTANT_VIEW->value)        => $this->findByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
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
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }

    private function findByAdminChurch()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }

    private function findByAdminDepartment()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }

    private function findByAssistant()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findByUserIdAndProfileUniqueName($this->adminUsersFiltersDTO);
    }
}
