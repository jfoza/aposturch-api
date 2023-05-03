<?php

namespace App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class FindAllProfilesByUserAbilityService extends Service implements FindAllProfilesByUserAbilityServiceInterface
{
    public function __construct(
        private readonly ProfilesRepositoryInterface $profileRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute()
    {
        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROFILES_SUPPORT_VIEW->value)      => $this->findAllBySupport(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value) => $this->findAllByAdminMaster(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_CHURCH_VIEW->value) => $this->findAllByAdminChurch(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MODULE_VIEW->value) => $this->findAllByAdminModule(),
            $policy->haveRule(RulesEnum::PROFILES_ASSISTANT_VIEW->value)    => $this->findAllByAssistant(),

            default  => $policy->dispatchErrorForbidden(),
        };
    }

    private function findAllBySupport()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }

    private function findAllByAdminMaster()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }

    private function findAllByAdminChurch()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }

    private function findAllByAdminModule()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }

    private function findAllByAssistant()
    {
        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ASSISTANT->value,
        ]);
    }
}
