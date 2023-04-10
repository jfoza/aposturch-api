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
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MODULE_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ASSISTANT_VIEW->value)    => $this->findByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findByAdminMaster()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_MODULE,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAdminChurch()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_MODULE,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAdminModule()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MODULE,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }

    private function findByAssistant()
    {
        $this->adminUsersFiltersDTO->profileUniqueName = [
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ];

        return $this->adminUsersRepository->findAll($this->adminUsersFiltersDTO);
    }
}
