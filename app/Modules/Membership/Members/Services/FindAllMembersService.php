<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;

class FindAllMembersService extends AuthenticatedService implements FindAllMembersServiceInterface
{
    private MembersFiltersDTO $membersFiltersDTO;

    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
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
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_VIEW->value)              => $this->findAllByMembers(),

            default => $policy->dispatchForbiddenError()
        };
    }

    private function findAllByAdminMaster(): LengthAwarePaginator|Collection
    {
        $this->membersFiltersDTO->churchIds = [$this->membersFiltersDTO->churchIdInQueryParam];

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function findAllByMembers(): LengthAwarePaginator|Collection
    {
        $this->membersFiltersDTO->churchIds = $this->getChurchesUserMember()->pluck(Church::ID)->toArray();

        if(isset($this->membersFiltersDTO->churchIdInQueryParam))
        {
            $this->churchIdExistsInChurchesUserLogged($this->membersFiltersDTO->churchIdInQueryParam);
            $this->membersFiltersDTO->churchIds = [$this->membersFiltersDTO->churchIdInQueryParam];
        }

        if(isset($this->membersFiltersDTO->profileId))
        {
            $this->profileIsAllowed($this->membersFiltersDTO->profileId);
        }

        return $this->membersRepository->findAll($this->membersFiltersDTO);
    }

    /**
     * @throws AppException
     */
    private function churchIdExistsInChurchesUserLogged(string $churchId)
    {
        $result = $this
            ->getChurchesUserMember()
            ->where(Church::ID, $churchId)
            ->first();

        if(empty($result))
        {
            throw new AppException(
                MessagesEnum::USER_CHURCH_RELATIONSHIP_NOT_FOUND,
                Response::HTTP_FORBIDDEN
            );
        }
    }

    /**
     * @throws AppException
     */
    private function profileIsAllowed(string $profileId)
    {
        if(!$profile = $this->profilesRepository->findById($profileId))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        $profilesAllowed = [
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
            ProfileUniqueNameEnum::ASSISTANT->value,
            ProfileUniqueNameEnum::MEMBER->value,
        ];

        if(!in_array($profile->unique_name, $profilesAllowed))
        {
            throw new AppException(
                MessagesEnum::PROFILE_NOT_ALLOWED,
                Response::HTTP_FORBIDDEN
            );
        }
    }
}
