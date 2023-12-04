<?php

namespace App\Modules\Membership\Members\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\UpdateStatusMemberServiceInterface;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateStatusMemberService extends MembersBaseService implements UpdateStatusMemberServiceInterface
{
    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        protected readonly UsersRepositoryInterface $usersRepository,
    )
    {
        parent::__construct($this->membersRepository);
    }

    private string $userId;
    private bool $status;

    /**
     * @throws AppException
     */
    public function execute(string $userId): array
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value) => $this->updateStatusByAdminMaster(),
            $policy->haveRule(RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value) => $this->updateStatusByAdminChurch(),
            $policy->haveRule(RulesEnum::USERS_ADMIN_MODULE_UPDATE_STATUS->value) => $this->updateStatusByAdminModule(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminMaster(): array
    {
        $userMember = $this->findOrFail($this->userId);

        $this->status = !$userMember->active;

        return $this->handleUpdateStatus();
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminChurch(): array
    {
        $userMember = $this->findOrFailWithHierarchyInUpdate(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_CHURCH->value
        );

        $this->status = !$userMember->active;

        return $this->handleUpdateStatus();
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminModule(): array
    {
        $userMember = $this->findOrFailWithHierarchyInUpdate(
            $this->userId,
            ProfileUniqueNameEnum::ADMIN_MODULE->value
        );

        $this->status = !$userMember->active;

        return $this->handleUpdateStatus();
    }

    /**
     * @return bool[]
     * @throws AppException
     */
    private function handleUpdateStatus(): array
    {
        Transaction::beginTransaction();

        try
        {
            $this->usersRepository->saveStatus($this->userId, $this->status);

            Transaction::commit();

            return ['status' => $this->status];
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
