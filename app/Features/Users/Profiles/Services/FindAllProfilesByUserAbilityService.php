<?php

namespace App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;

class FindAllProfilesByUserAbilityService implements FindAllProfilesByUserAbilityServiceInterface
{
    public function __construct(
        private readonly ProfilesRepositoryInterface $profileRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(Policy $policy)
    {
        return match (true) {
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value)     => $this->findAllByAdminMaster(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_CHURCH_VIEW->value)     => $this->findAllByAdminChurch(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_DEPARTMENT_VIEW->value) => $this->findAllByAdminDepartment(),
            $policy->haveRule(RulesEnum::PROFILES_ASSISTANT_VIEW->value)        => $this->findAllByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findAllByAdminMaster()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_MASTER,
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ]);
    }

    private function findAllByAdminChurch()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_CHURCH,
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ]);
    }

    private function findAllByAdminDepartment()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_DEPARTMENT,
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ]);
    }

    private function findAllByAssistant()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ASSISTANT,
            ProfileUniqueNameEnum::MEMBER,
        ]);
    }
}
