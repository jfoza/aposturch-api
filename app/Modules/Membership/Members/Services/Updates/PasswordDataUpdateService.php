<?php

namespace App\Modules\Membership\Members\Services\Updates;

use App\Base\Traits\AutomaticLogoutTrait;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\Updates\PasswordDataUpdateServiceInterface;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;
use App\Modules\Membership\Members\Services\MembersBaseService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;
use Exception;

class PasswordDataUpdateService extends MembersBaseService implements PasswordDataUpdateServiceInterface
{
    use AutomaticLogoutTrait;

    private string $userId;
    private string $password;

    public function __construct(
        protected MembersRepositoryInterface $membersRepository,
        private readonly UsersRepositoryInterface   $usersRepository,
        private readonly UpdateMemberResponse       $updateMemberResponse,
    ) {
        parent::__construct($this->membersRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(string $userId, string $password): UpdateMemberResponse
    {
        $this->userId = $userId;
        $this->password = $password;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MASTER_UPDATE->value)
                => $this->updateByAdminMaster(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_CHURCH_UPDATE->value)
                => $this->updateByAdminChurch(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ADMIN_MODULE_UPDATE->value)
                => $this->updateByAdminModule(),

            $policy->haveRule(RulesEnum::MEMBERSHIP_MODULE_MEMBERS_ASSISTANT_UPDATE->value)
                => $this->updateByAssistant(),

            default => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): UpdateMemberResponse
    {
        $this->findOrFail($this->userId);

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminChurch(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $this->findOrFailWithHierarchyInUpdate(
                $this->userId,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value,
            );
        }

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminModule(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $this->findOrFailWithHierarchyInUpdate(
                $this->userId,
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
            );
        }

        return $this->updateMemberData();
    }

    /**
     * @throws AppException
     */
    private function updateByAssistant(): UpdateMemberResponse
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $this->findOrFailWithHierarchyInUpdate(
                $this->userId,
                ProfileUniqueNameEnum::ASSISTANT->value,
            );
        }

        return $this->updateMemberData();
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
            $encryptedPassword = Hash::generateHash($this->password);

            $this->usersRepository->saveNewPassword($this->userId, $encryptedPassword);

            $this->updateMemberResponse->id = $this->userId;

            $this->invalidateSessionsUser($this->userId);

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
