<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Base\Validations\ProfileHierarchyValidation;
use App\Exceptions\AppException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Validations\AdminUsersValidations;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;

class UpdateAdminUserService extends AuthenticatedService implements UpdateAdminUserServiceInterface
{
    private UserDTO $userDTO;

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
        $adminUser = $this->findOrFail();

        ProfileHierarchyValidation::handleBaseValidationInPersistence(
            $adminUser->profile->pluck(Profile::UNIQUE_NAME)->toArray(),
            [
                ProfileUniqueNameEnum::TECHNICAL_SUPPORT->value,
                ProfileUniqueNameEnum::ADMIN_MASTER->value,
            ]
        );

        return $this->updateBaseOperation();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): AdminUserResponse
    {
        $adminUser = $this->findOrFail();

        ProfileHierarchyValidation::handleBaseValidationInPersistence(
            $adminUser->profile->pluck(Profile::UNIQUE_NAME)->toArray(),
            [ProfileUniqueNameEnum::ADMIN_MASTER->value]
        );

        return $this->updateBaseOperation();
    }

    /**
     * @throws AppException
     */
    private function findOrFail(): object
    {
        if(!$adminUserById = $this->adminUsersRepository->findByUserId($this->userDTO->id))
        {
            AdminUsersValidations::adminUserNotFoundException();
        }

        $adminUserByEmail = $this->adminUsersRepository->findByUserEmail($this->userDTO->email);

        if($adminUserByEmail && $adminUserByEmail->id != $this->userDTO->id)
        {
            UsersValidations::emailAlreadyExistsUpdateException();
        }

        return $adminUserById;
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

            if(!is_null($this->userDTO->passwordDTO->password))
            {
                $newPassword = Hash::generateHash($this->userDTO->passwordDTO->password);

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

            return $this->adminUserResponse;
        } catch(\Exception $e) {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
