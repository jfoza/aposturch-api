<?php

namespace App\Features\Users\Profiles\Business;

use App\Shared\Enums\RulesEnum;
use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\Profiles\Contracts\ProfilesListFactoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;

class ProfilesListFactory
    extends Business
    implements ProfilesListFactoryInterface
{
    private mixed $profiles;

    public function __construct(
        private readonly ProfilesRepositoryInterface $profileRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute()
    {
        return $this
                ->findByAdminMasterRule()
                ->findByEmployeeRule()
                ->profiles
            ?? $this->getPolicy()->dispatchErrorForbidden();
    }

    private function findByAdminMasterRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::PROFILES_ADMIN_MASTER_VIEW->value)) {
            $this->profiles = $this->profileRepository->findAllByUniqueName([
                ProfileUniqueNameEnum::ADMIN_MASTER,
                ProfileUniqueNameEnum::EMPLOYEE,
            ]);
        }

        return $this;
    }

    private function findByEmployeeRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::PROFILES_EMPLOYEE_VIEW->value)) {
            $this->profiles = $this->profileRepository->findAllByUniqueName([
                ProfileUniqueNameEnum::EMPLOYEE,
            ]);
        }

        return $this;
    }
}
