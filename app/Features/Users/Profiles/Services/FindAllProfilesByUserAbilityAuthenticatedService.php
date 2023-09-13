<?php

namespace App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class FindAllProfilesByUserAbilityAuthenticatedService extends AuthenticatedService implements FindAllProfilesByUserAbilityServiceInterface
{
    private ProfilesFiltersDTO $profilesFiltersDTO;

    public function __construct(
        private readonly ProfilesRepositoryInterface $profileRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ProfilesFiltersDTO $profilesFiltersDTO)
    {
        $this->profilesFiltersDTO = $profilesFiltersDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::PROFILES_SUPPORT_VIEW->value)      => $this->findAllBySupport(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value) => $this->findAllByAdminMaster(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_CHURCH_VIEW->value) => $this->findAllByAdminChurch(),
            $policy->haveRule(RulesEnum::PROFILES_ADMIN_MODULE_VIEW->value) => $this->findAllByAdminModule(),
            $policy->haveRule(RulesEnum::PROFILES_ASSISTANT_VIEW->value)    => $this->findAllByAssistant(),

            default  => $policy->dispatchForbiddenError(),
        };
    }

    private function findAllBySupport()
    {
        return $this->profileRepository->findAll($this->profilesFiltersDTO);
    }

    private function findAllByAdminMaster()
    {
        $this->profilesFiltersDTO->profilesUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        return $this->profileRepository->findAll($this->profilesFiltersDTO);
    }

    private function findAllByAdminChurch()
    {
        $this->profilesFiltersDTO->profilesUniqueName = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        return $this->profileRepository->findAll($this->profilesFiltersDTO);
    }

    private function findAllByAdminModule()
    {
        $this->profilesFiltersDTO->profilesUniqueName = [
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        return $this->profileRepository->findAll($this->profilesFiltersDTO);
    }

    private function findAllByAssistant()
    {
        $this->profilesFiltersDTO->profilesUniqueName = [
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        return $this->profileRepository->findAll($this->profilesFiltersDTO);
    }
}
