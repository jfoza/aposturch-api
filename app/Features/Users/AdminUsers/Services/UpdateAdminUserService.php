<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Validations\AdminUsersValidations;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;

class UpdateAdminUserService extends Service implements UpdateAdminUserServiceInterface
{
    private UserDTO $userDTO;
    private mixed $profile;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly UsersRepositoryInterface      $usersRepository,
        private readonly AdminUserResponse             $adminUserResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserDTO $userDTO): AdminUserResponse
    {
        $this->userDTO = $userDTO;

        $policy = $this->getPolicy();

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_SUPPORT_UPDATE->value)      => $this->updateBySupport(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value) => $this->updateByAdminMaster(),

            default  => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateBySupport(): AdminUserResponse
    {
        $profilesAllowed = [
            ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
            ProfileUniqueNameEnum::ADMIN_MASTER->value
        ];

        $this->handleValidations($profilesAllowed);

        return $this->updateBaseOperation();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): AdminUserResponse
    {
        $profilesAllowed = [
            ProfileUniqueNameEnum::ADMIN_MASTER->value
        ];

        $this->handleValidations($profilesAllowed);

        return $this->updateBaseOperation();
    }

    /**
     * @throws AppException
     */
    private function handleValidations(array $profiles)
    {
        if(!$this->adminUsersRepository->findById($this->userDTO->id, $profiles))
        {
            AdminUsersValidations::adminUserNotFoundException();
        }

        $user = $this->adminUsersRepository->findByEmail($this->userDTO->email, $profiles);

        if($user && $user->id != $this->userDTO->id)
        {
            UsersValidations::emailAlreadyExistsUpdateException();
        }
    }

    /**
     * @return AdminUserResponse
     * @throws AppException
     */
    private function updateBaseOperation(): AdminUserResponse
    {
        Transaction::beginTransaction();

        try {
            $this->usersRepository->save($this->userDTO);

            if(!is_null($this->userDTO->password))
            {
                $newPassword = Hash::generateHash($this->userDTO->password);

                $this->usersRepository->saveNewPassword(
                    $this->userDTO->id,
                    $newPassword
                );
            }

            PolicyCache::invalidatePolicy($this->userDTO->id);

            Transaction::commit();

            $this->adminUserResponse->id     = $this->userDTO->id;
            $this->adminUserResponse->name   = $this->userDTO->name;
            $this->adminUserResponse->email  = $this->userDTO->email;
            $this->adminUserResponse->active = $this->userDTO->active;

            return $this->adminUserResponse;
        } catch(\Exception $e) {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
