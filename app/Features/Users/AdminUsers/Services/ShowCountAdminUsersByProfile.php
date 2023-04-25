<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\ShowCountAdminUsersByProfileInterface;
use App\Features\Users\AdminUsers\Responses\CountAdminUsersResponse;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class ShowCountAdminUsersByProfile extends Service implements ShowCountAdminUsersByProfileInterface
{
    public function __construct(
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly CountAdminUsersResponse $countAdminUsersResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(): CountAdminUsersResponse
    {
        $policy = $this->getPolicy();

        match (true) {
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_MASTER_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_CHURCH_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_MODULE_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ASSISTANT_VIEW->value)    => $this->findByAssistant(),

            default => $policy->dispatchErrorForbidden(),
        };

        return $this->countAdminUsersResponse;
    }

    private function findByAdminMaster(): void
    {
        $this->countAdminUsersResponse->adminMasterCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_MASTER->value);

        $this->countAdminUsersResponse->adminChurchCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_CHURCH->value);

        $this->countAdminUsersResponse->adminModuleCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_MODULE->value);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT->value);
    }

    private function findByAdminChurch(): void
    {
        $this->countAdminUsersResponse->adminChurchCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_CHURCH->value);

        $this->countAdminUsersResponse->adminModuleCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_MODULE->value);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT->value);
    }

    private function findByAdminModule(): void
    {
        $this->countAdminUsersResponse->adminModuleCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_MODULE->value);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT->value);
    }

    private function findByAssistant(): void
    {
        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT->value);
    }
}
