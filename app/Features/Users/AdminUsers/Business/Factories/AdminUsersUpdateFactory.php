<?php

namespace App\Features\Users\AdminUsers\Business\Factories;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersUpdateFactoryInterface;
use App\Features\Users\AdminUsers\Services\AdminUsersValidationsService;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Infra\Models\Profile;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Infra\Models\User;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;
use App\Shared\Cache\PolicyCache;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class AdminUsersUpdateFactory
    extends Business
    implements AdminUsersUpdateFactoryInterface
{
    private UserDTO $userDTO;
    private mixed $adminUserUpdated;
    private mixed $profile;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly ProfilesRepositoryInterface $profilesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(UserDTO $userDTO)
    {
        $this->userDTO = $userDTO;

        return $this
                ->updateByAdminMasterRule()
                ->updateByEmployeeRule()
                ->adminUserUpdated
            ?? $this->getPolicy()->dispatchErrorForbidden();
    }

    /**
     * @throws AppException
     */
    private function updateByAdminMasterRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_UPDATE->value)) {
            $this->handleInitialValidation();

            $this->updateBaseOperation();
        }

        return $this;
    }

    /**
     * @throws AppException
     */
    private function updateByEmployeeRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_UPDATE->value)) {
            $this->handleInitialValidation();

            if($this->profile->unique_name != ProfileUniqueNameEnum::EMPLOYEE) {
                $this->getPolicy()->dispatchErrorForbidden();
            }

            $this->updateBaseOperation();
        }

        return $this;
    }

    /**
     * @throws AppException
     */
    private function handleInitialValidation()
    {
        AdminUsersValidationsService::adminUserIdExists($this->adminUsersRepository, $this->userDTO->id);
        UsersValidationsService::emailAlreadyExistsUpdate($this->usersRepository, $this->userDTO->id, $this->userDTO->email);
        $this->profile = UsersValidationsService::returnProfileExists($this->profilesRepository, $this->userDTO->profileId);
    }

    /**
     * @throws AppException
     */
    private function updateBaseOperation()
    {
        Transaction::beginTransaction();

        try {
            $this->usersRepository->save($this->userDTO);

            $this->usersRepository->saveProfiles($this->userDTO->id, [$this->userDTO->profileId]);

            PolicyCache::invalidatePolicy($this->userDTO->id);

            Transaction::commit();

            $this->adminUserUpdated = [
                User::ID     => $this->userDTO->id,
                User::NAME   => $this->userDTO->name,
                User::EMAIL  => $this->userDTO->email,
                User::ACTIVE => $this->userDTO->active,
                'profile' => [
                    Profile::ID => $this->userDTO->profileId,
                    Profile::DESCRIPTION => $this->profile->description,
                ],
            ];
        } catch(\Exception $e) {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
