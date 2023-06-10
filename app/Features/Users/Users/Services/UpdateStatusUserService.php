<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\AuthenticatedService;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UpdateStatusUserServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
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
        private readonly MembersFiltersDTO $membersFiltersDTO,
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

        $this->status = !$user->active;

        return $this->handleUpdateStatus();
    }

    /**
     * @throws AppException
     */
    private function updateStatusByAdminChurch(): array
    {
        if(!$this->userPayloadIsEqualsAuthUser($this->userId))
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $this->membersFiltersDTO->churchIds = $this->getUserMemberChurchIds();

        $userMember = MembersValidations::memberExists(
            $this->userId,
            $this->membersFiltersDTO,
            $this->membersRepository
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
