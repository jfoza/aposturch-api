<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Validations\ProfileHierarchyValidation;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllMembersService extends AuthenticatedService implements FindAllMembersServiceInterface
{
    private MembersFiltersDTO $membersFiltersDTO;

    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(MembersFiltersDTO $membersFiltersDTO): LengthAwarePaginator|Collection
    {
        $policy = $this->getPolicy();

        $this->membersFiltersDTO = $membersFiltersDTO;

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_VIEW->value) => $this->findAllByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_VIEW->value) => $this->findAllByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_VIEW->value) => $this->findAllByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_VIEW->value)    => $this->findAllByAssistant(),

            default => $policy->dispatchForbiddenError()
        };
    }

    private function findAllByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->membersFiltersDTO->profilesUniqueName = ProfileHierarchyValidation::PROFILES_BY_ADMIN_CHURCH;

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findAllByAdminChurch(): LengthAwarePaginator|Collection
    {
        $this->findOrFailByChurch();
        $this->findOrFailByProfile();

        $this->membersFiltersDTO->profilesUniqueName = ProfileHierarchyValidation::PROFILES_BY_ADMIN_CHURCH;

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findAllByAdminModule(): LengthAwarePaginator|Collection
    {
        $this->findOrFailByChurch();
        $this->findOrFailByProfile();

        $this->membersFiltersDTO->modulesId = $this->getUserModulesId();

        $this->membersFiltersDTO->profilesUniqueName = ProfileHierarchyValidation::PROFILES_BY_ADMIN_MODULE;

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findAllByAssistant(): LengthAwarePaginator|Collection
    {
        $this->findOrFailByChurch();
        $this->findOrFailByProfile();

        $this->membersFiltersDTO->modulesId = $this->getUserModulesId();

        $this->membersFiltersDTO->profilesUniqueName = ProfileHierarchyValidation::PROFILES_BY_ASSISTANT;

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findOrFailByChurch(): void
    {
        if(isset($this->membersFiltersDTO->churchesId))
        {
            $this->canAccessTheChurch($this->membersFiltersDTO->churchesId);
        }
        else
        {
            $this->membersFiltersDTO->churchesId = $this->getUserMemberChurchesId();
        }
    }

    /**
     * @throws AppException
     */
    private function findOrFailByProfile(): void
    {
        if(isset($this->membersFiltersDTO->profileId))
        {
            $this->canAccessProfile($this->membersFiltersDTO->profileId);
        }
    }
}
