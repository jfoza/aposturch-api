<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Exceptions\AppException;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Validations\ChurchValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\ChurchDataUpdateServiceInterface;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Exception;

class ChurchDataUpdateService extends MembersBaseService implements ChurchDataUpdateServiceInterface
{
    private string $userId;
    private string $churchId;
    private mixed $userMember;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        private readonly ChurchRepositoryInterface  $churchRepository,
        private readonly UpdateMemberResponse       $updateMemberResponse,
    ) {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId, string $churchId): UpdateMemberResponse
    {
        $this->userId = $userId;
        $this->churchId = $churchId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value)
                => $this->updateByAdminChurch(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFail($this->userId);

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        $this->userMember = $this->findOrFailWithHierarchy(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value,
        );

        $this->handleGeneralValidations();

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function handleGeneralValidations(): void
    {
        ChurchValidations::churchIdExists(
            $this->churchRepository,
            $this->churchId
        );
    }

    /**
     * @return UpdateMemberResponse
     * @throws AppException
     */
    public function updateMemberData(): UpdateMemberResponse
    {
        Transaction::beginTransaction();

        try
        {
            $memberId = $this->userMember->member_id;

            $this->membersRepository->saveMembers($memberId, [$this->churchId]);

            $this->updateMemberResponse->id       = $this->userId;
            $this->updateMemberResponse->churchId = $this->churchId;

            Transaction::commit();

            return $this->updateMemberResponse;
        }
        catch (Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
