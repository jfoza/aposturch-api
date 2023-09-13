<?php

namespace App\Modules\Membership\Members\Services;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Responses\MemberResponse;
use App\Shared\Enums\RulesEnum;

class ShowByUserIdService extends MembersBaseService implements ShowByUserIdServiceInterface
{
    private string $userId;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        protected readonly MemberResponse $memberResponse,
    )
    {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId): MemberResponse
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
    private function findByAdminMaster(): MemberResponse
    {
        $member = $this->findOrFail($this->userId);

        $this->memberResponse->setMemberResponse($member);

        return $this->memberResponse;
    }

    /**
     * @throws AppException
     */
    private function findByAdminChurch(): MemberResponse
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        );

        $this->memberResponse->setMemberResponse($member);

        return $this->memberResponse;
    }

    /**
     * @throws AppException
     */
    private function findByAdminModule(): MemberResponse
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_MODULE->value,
        );

        $this->memberResponse->setMemberResponse($member);

        return $this->memberResponse;
    }

    /**
     * @throws AppException
     */
    private function findByAssistant(): MemberResponse
    {
        $member = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ASSISTANT->value,
        );

        $this->memberResponse->setMemberResponse($member);

        return $this->memberResponse;
    }
}
