<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Services\Utils\HashService;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;
use App\Shared\ACL\Policy;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateAdminUserService implements CreateAdminUserServiceInterface
{
    use DispatchExceptionTrait;

    private UserDTO $userDTO;
    private mixed $profile;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
        private readonly AdminUserResponse $adminUserResponse,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(
        UserDTO $userDTO,
        Policy $policy
    ): AdminUserResponse
    {
        $this->userDTO = $userDTO;

        UsersValidationsService::emailAlreadyExists($this->usersRepository, $this->userDTO->email);

        $this->profile = UsersValidationsService::returnProfileExists($this->profilesRepository, $this->userDTO->profileId);

        $this->userDTO->newPasswordGenerationsDTO->passwordEncrypt = HashService::generateHash($this->userDTO->password);

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_VIEW->value) => $this->createByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_VIEW->value)     => $this->createByEmployee(),
        };
    }

    /**
     * @throws AppException
     */
    private function createByAdminMaster(): AdminUserResponse
    {
        return $this->baseInsertOperation();
    }

    /**
     * @throws AppException
     */
    private function createByEmployee(): AdminUserResponse
    {
        AllowedProfilesValidations::validateEmployeeProfile($this->profile->unique_name);

        return $this->baseInsertOperation();
    }

    /**
     * @return AdminUserResponse
     * @throws AppException
     */
    private function baseInsertOperation(): AdminUserResponse
    {
        Transaction::beginTransaction();

        try {
            $user = $this->usersRepository->create($this->userDTO);
            $this->userDTO->id = $user->id;

            $this->adminUsersRepository->create($this->userDTO->id);

            $this->usersRepository->saveProfiles($this->userDTO->id, [$this->userDTO->profileId]);

            Transaction::commit();

            $this->adminUserResponse->id                 = $user->id;
            $this->adminUserResponse->name               = $user->name;
            $this->adminUserResponse->email              = $user->email;
            $this->adminUserResponse->active             = $user->active;
            $this->adminUserResponse->profileId          = $this->profile->id;
            $this->adminUserResponse->profileDescription = $this->profile->description;

            return $this->adminUserResponse;
        } catch(\Exception $e) {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
