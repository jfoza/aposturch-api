<?php

namespace App\Features\Users\Profiles\Services;

use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;

readonly class FindAllProfilesByUserAbilityService implements FindAllProfilesByUserAbilityServiceInterface
{
    public function __construct(
        private ProfilesRepositoryInterface $profileRepository,
    ) {}

    public function execute(Policy $policy)
    {
        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->findAllByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value)     => $this->findAllByEmployee(),
        };
    }

    private function findAllByAdminMaster()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::EMPLOYEE,
        ]);
    }

    private function findAllByEmployee()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::EMPLOYEE,
        ]);
    }
}
