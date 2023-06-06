<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class ShowByUserIdService extends Service implements ShowByUserIdServiceInterface
{
    private string $userId;

    public function __construct(
        private readonly MembersRepositoryInterface $membersRepository,
        private readonly MembersFiltersDTO $membersFiltersDTO
    ) {}

    /**
     * @throws AppException
     * @throws UserNotDefinedException
     */
    public function execute(string $userId): object
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_DETAILS_VIEW->value) => $this->findByAdminMaster(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_DETAILS_VIEW->value) => $this->findByAdminChurch(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_DETAILS_VIEW->value) => $this->findByAdminModule(),
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_DETAILS_VIEW->value)    => $this->findByAssistant(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function findByAdminMaster(): object
    {
        return $this->findOrFail();
    }

    /**
     * @throws UserNotDefinedException
     * @throws AppException
     */
    private function findByAdminChurch(): object
    {
        if(Auth::getId() != $this->userId)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_CHURCH->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $member = $this->findOrFail();

        MembersValidations::memberUserHasChurch(
            $member,
            $this->getChurchesUserMember()
        );

        return $member;
    }

    /**
     * @throws UserNotDefinedException
     * @throws AppException
     */
    private function findByAdminModule(): object
    {
        if(Auth::getId() != $this->userId)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $member = $this->findOrFail();

        MembersValidations::memberUserHasChurch(
            $member,
            $this->getChurchesUserMember()
        );

        return $member;
    }

    /**
     * @throws UserNotDefinedException
     * @throws AppException
     */
    private function findByAssistant(): object
    {
        if(Auth::getId() != $this->userId)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $member = $this->findOrFail();

        MembersValidations::memberUserHasChurch(
            $member,
            $this->getChurchesUserMember()
        );

        return $member;
    }

    /**
     * @throws AppException
     */
    private function findOrFail(): object
    {
        if(!$member = $this->membersRepository->findOneByFilters($this->userId, $this->membersFiltersDTO))
        {
            throw new AppException(
                MessagesEnum::USER_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $member;
    }
}
