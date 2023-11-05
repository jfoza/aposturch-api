<?php

namespace App\Features\Users\Users\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Base\Validations\ProfileHierarchyValidation;
use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateStatusAdminUserService extends AuthenticatedService
{
    private string $userId;
    private bool $status;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
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
            $policy->haveRule(RulesEnum::USERS_TECHNICAL_SUPPORT_UPDATE_STATUS->value)
                => $this->updateStatusBySupport(),

            $policy->haveRule(RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value)
                => $this->updateStatusByAdminMaster(),

            default => $policy->dispatchForbiddenError()
        };
    }

    /**
     * @throws AppException
     */
    private function updateStatusBySupport(): array
    {
        $user = UsersValidations::validateUserExistsById(
            $this->userId,
            $this->usersRepository
        );

        $profilesUniqueName = $user->profile->pluck(Profile::UNIQUE_NAME)->toArray();

        ProfileHierarchyValidation::handleBaseValidationInPersistence(
            $profilesUniqueName,
            [
                ProfileUniqueNameEnum::ADMIN_MASTER->value,
                ProfileUniqueNameEnum::ADMIN_CHURCH->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ]
        );

        $this->status = !$user->active;

        return $this->handleUpdateStatus();
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

        $profilesUniqueName = $user->profile->pluck(Profile::UNIQUE_NAME)->toArray();

        ProfileHierarchyValidation::handleBaseValidationInPersistence(
            $profilesUniqueName,
            [
                ProfileUniqueNameEnum::ADMIN_CHURCH->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ]
        );

        $this->status = !$user->active;

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
