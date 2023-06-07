<?php

namespace App\Features\Users\Profiles\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Users\Profiles\Contracts\FindAllProfilesInListMembersServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\RulesEnum;

class FindAllProfilesInListMembersAuthenticatedService extends AuthenticatedService implements FindAllProfilesInListMembersServiceInterface
{
    public function __construct(
        private readonly ProfilesRepositoryInterface $profileRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute()
    {
        $this->getPolicy()->havePermission(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_PROFILES_FILTER_VIEW->value);

        return $this->profileRepository->findAllByUniqueName([
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ]);
    }
}
