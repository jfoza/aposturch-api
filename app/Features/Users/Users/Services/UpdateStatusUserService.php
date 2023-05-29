<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UpdateStatusUserServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Validations\MembersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Auth;
use App\Shared\Utils\Transaction;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class UpdateStatusUserService extends Service implements UpdateStatusUserServiceInterface
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
     * @throws UserNotDefinedException
     */
    public function execute(string $userId): array
    {
        $this->userId = $userId;

        $policy = $this->getPolicy();

        return match (true)
        {
            $policy->haveRule(RulesEnum::USERS_ADMIN_MASTER_UPDATE_STATUS->value) => $this->updateStatusByAdminMaster(),
            $policy->haveRule(RulesEnum::USERS_ADMIN_CHURCH_UPDATE_STATUS->value) => $this->updateStatusByAdminChurch(),

            default => $policy->dispatchErrorForbidden()
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
     * @throws UserNotDefinedException
     */
    private function updateStatusByAdminChurch(): array
    {
        if(Auth::getId() != $this->userId)
        {
            $this->membersFiltersDTO->profileUniqueName = [
                ProfileUniqueNameEnum::ADMIN_MASTER->value,
                ProfileUniqueNameEnum::ADMIN_MODULE->value,
                ProfileUniqueNameEnum::ASSISTANT->value,
                ProfileUniqueNameEnum::MEMBER->value,
            ];
        }

        $userMember = MembersValidations::memberExists(
            $this->userId,
            $this->membersFiltersDTO,
            $this->membersRepository
        );

        MembersValidations::memberUserHasChurch(
            $userMember,
            $this->getChurchesUserMember()
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
