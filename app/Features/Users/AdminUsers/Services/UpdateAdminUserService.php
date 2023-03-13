<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\AdminUsers\Validations\AdminUsersValidations;
use App\Features\Users\AdminUsers\Validations\AllowedProfilesValidations;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;
use App\Shared\ACL\Policy;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateAdminUserService implements UpdateAdminUserServiceInterface
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

        AdminUsersValidations::adminUserIdExists($this->adminUsersRepository, $this->userDTO->id);
        UsersValidationsService::emailAlreadyExistsUpdate($this->usersRepository, $this->userDTO->id, $this->userDTO->email);
        $this->profile = UsersValidationsService::returnProfileExists($this->profilesRepository, $this->userDTO->profileId);

        return match (true) {
            $policy->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value) => $this->updateByAdminMaster(),
            $policy->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_UPDATE->value)     => $this->updateByEmployee(),
        };
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMaster(): AdminUserResponse
    {
        return $this->updateBaseOperation();
    }

    /**
     * @throws AppException
     */
    private function updateByEmployee(): AdminUserResponse
    {
        AllowedProfilesValidations::validateEmployeeProfile($this->profile->unique_name);

        return $this->updateBaseOperation();
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

            $this->usersRepository->saveProfiles($this->userDTO->id, [$this->userDTO->profileId]);

            PolicyCache::invalidatePolicy($this->userDTO->id);

            Transaction::commit();

            $this->adminUserResponse->id                 = $this->userDTO->id;
            $this->adminUserResponse->name               = $this->userDTO->name;
            $this->adminUserResponse->email              = $this->userDTO->email;
            $this->adminUserResponse->active             = $this->userDTO->active;
            $this->adminUserResponse->profileId          = $this->profile->id;
            $this->adminUserResponse->profileDescription = $this->profile->description;

            return $this->adminUserResponse;
        } catch(\Exception $e) {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
