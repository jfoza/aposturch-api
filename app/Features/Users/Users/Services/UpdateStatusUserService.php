<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Profiles\Validations\ProfileHierarchyValidations;
use App\Features\Users\Users\Contracts\UpdateStatusUserServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Church\Utils\ChurchUtils;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateStatusUserService extends AuthenticatedService implements UpdateStatusUserServiceInterface
{
    private string $userId;
    private bool $status;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly MembersRepositoryInterface $membersRepository,
    ) {}

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

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminMaster(): array
    {
        $user = UsersValidations::validateUserExistsById(
            $this->userId,
            $this->usersRepository
        );

        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $profilesId = $user->profile->pluck(Profile::UNIQUE_NAME)->toArray();

            ProfileHierarchyValidations::adminMasterValidation($profilesId);
        }

        $this->status = !$user->active;

        return $this->handleUpdateStatus();
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminChurch(): array
    {
        $userMember = MembersValidations::memberExists(
            $this->userId,
            $this->membersRepository
        );

        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            ProfileHierarchyValidations::adminChurchValidation([$userMember->profile_unique_name]);
        }

        $churchesId = ChurchUtils::extractChurchesId($userMember);

        $this->userHasAccessToChurch($churchesId);

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
