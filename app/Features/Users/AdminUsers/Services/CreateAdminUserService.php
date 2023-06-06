<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Services\Service;
use App\Features\Base\Traits\EnvironmentException;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Hash;
use App\Shared\Utils\Transaction;

class CreateAdminUserService extends Service implements CreateAdminUserServiceInterface
{
    private UserDTO $userDTO;
    private mixed $profile;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly UsersRepositoryInterface      $usersRepository,
        private readonly ProfilesRepositoryInterface   $profilesRepository,
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
            $policy->haveRule(RulesEnum::ADMIN_USERS_SUPPORT_INSERT->value)      => $this->createBySupport(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_INSERT->value) => $this->createByAdminMaster(),

            default  => $policy->dispatchForbiddenError(),
        };
    }

    /**
     * @throws AppException
     */
    private function createBySupport(): AdminUserResponse
    {
        $this->handleValidations();

        AllowedProfilesValidations::validateSupportProfile($this->profile->unique_name);

        return $this->baseInsertOperation();
    }

    /**
     * @throws AppException
     */
    private function createByAdminMaster(): AdminUserResponse
    {
        $this->handleValidations();

        AllowedProfilesValidations::validateAdminMasterProfile($this->profile->unique_name);

        return $this->baseInsertOperation();
    }

    /**
     * @throws AppException
     */
    private function handleValidations()
    {
        UsersValidations::emailAlreadyExists($this->usersRepository, $this->userDTO->email);

        $this->profile = UsersValidations::returnProfileExists($this->profilesRepository, $this->userDTO->profileId);
    }

    /**
     * @return AdminUserResponse
     * @throws AppException
     */
    private function baseInsertOperation(): AdminUserResponse
    {
        Transaction::beginTransaction();

        try
        {
            $this->userDTO->newPasswordGenerationsDTO->passwordEncrypt = Hash::generateHash($this->userDTO->password);

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
        }
        catch(\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
