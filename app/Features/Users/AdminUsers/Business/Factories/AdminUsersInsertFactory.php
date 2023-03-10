<?php

namespace App\Features\Users\AdminUsers\Business\Factories;

use App\Exceptions\AppException;
use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersInsertFactoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Services\Utils\HashService;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class AdminUsersInsertFactory
    extends Business
    implements AdminUsersInsertFactoryInterface
{
    private UserDTO $userDTO;
    private mixed $adminUserCreated;
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
            ->insertByAdminMasterRule()
            ->insertByEmployeeRule()
            ->adminUserCreated
            ?? $this->getPolicy()->dispatchErrorForbidden();
    }

    /**
     * @throws AppException
     */
    private function insertByAdminMasterRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_ADMIN_MASTER_INSERT->value)) {
            $this->handleInitialValidation();

            $this->userDTO->newPasswordGenerationsDTO->passwordEncrypt = HashService::generateHash($this->userDTO->password);

            $this->baseInsertOperation();
        }

        return $this;
    }

    /**
     * @throws AppException
     */
    private function insertByEmployeeRule(): static
    {
        if($this->getPolicy()->haveRule(RulesEnum::ADMIN_USERS_EMPLOYEE_INSERT->value)) {
            $this->handleInitialValidation();

            if($this->profile->unique_name != ProfileUniqueNameEnum::EMPLOYEE) {
                $this->getPolicy()->dispatchErrorForbidden();
            }

            $this->userDTO->newPasswordGenerationsDTO->passwordEncrypt = HashService::generateHash($this->userDTO->password);

            $this->baseInsertOperation();
        }

        return $this;
    }

    /**
     * @throws AppException
     */
    private function handleInitialValidation()
    {
        UsersValidationsService::emailAlreadyExists($this->usersRepository, $this->userDTO->email);
        $this->profile = UsersValidationsService::returnProfileExists($this->profilesRepository, $this->userDTO->profileId);
    }

    /**
     * @throws AppException
     */
    private function baseInsertOperation()
    {
        Transaction::beginTransaction();

        try {
            $user = $this->usersRepository->create($this->userDTO);
            $this->userDTO->id = $user->id;

            $this->adminUsersRepository->create($this->userDTO->id);

            $this->usersRepository->saveProfiles($this->userDTO->id, [$this->userDTO->profileId]);

            Transaction::commit();

            $this->adminUserCreated = [
                'user' => $user,
                'profile' => $this->profile
            ];
        } catch(\Exception $e) {
            Transaction::rollback();

            $this->dispatchException($e);
        }
    }
}
