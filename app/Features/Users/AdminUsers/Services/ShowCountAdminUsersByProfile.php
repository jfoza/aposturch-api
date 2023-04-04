<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\ShowCountAdminUsersByProfileInterface;
use App\Features\Users\AdminUsers\Http\Responses\CountAdminUsersResponse;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;

class ShowCountAdminUsersByProfile implements ShowCountAdminUsersByProfileInterface
{
    public function __construct(
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly CountAdminUsersResponse $countAdminUsersResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(Policy $policy): CountAdminUsersResponse
    {
        match (true) {
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_MASTER_VIEW->value)     => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_CHURCH_VIEW->value)     => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ADMIN_DEPARTMENT_VIEW->value) => $this->findByAdminDepartment(),
            $policy->haveRule(RulesEnum::COUNT_USERS_ASSISTANT_VIEW->value)        => $this->findByAssistant(),

            default => $policy->dispatchErrorForbidden(),
        };

        return $this->countAdminUsersResponse;
    }

    private function findByAdminMaster(): void
    {
        $this->countAdminUsersResponse->adminMasterCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_MASTER);

        $this->countAdminUsersResponse->adminChurchCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_CHURCH);

        $this->countAdminUsersResponse->adminDepartmentCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_DEPARTMENT);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT);

        $this->countAdminUsersResponse->memberCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::MEMBER);
    }

    private function findByAdminChurch(): void
    {
        $this->countAdminUsersResponse->adminChurchCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_CHURCH);

        $this->countAdminUsersResponse->adminDepartmentCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_DEPARTMENT);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT);

        $this->countAdminUsersResponse->memberCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::MEMBER);
    }

    private function findByAdminDepartment(): void
    {
        $this->countAdminUsersResponse->adminDepartmentCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ADMIN_DEPARTMENT);

        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT);

        $this->countAdminUsersResponse->memberCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::MEMBER);
    }

    private function findByAssistant(): void
    {
        $this->countAdminUsersResponse->assistantCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::ASSISTANT);

        $this->countAdminUsersResponse->memberCount =
            $this->profilesRepository->findCountUsersByProfile(ProfileUniqueNameEnum::MEMBER);
    }
}
