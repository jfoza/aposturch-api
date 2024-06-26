<?php

namespace App\Features\Users\Users\Services;

use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\NewPasswordDTO;
use App\Features\Users\Users\Validations\UsersValidations;
use App\Shared\Cache\PolicyCache;
use App\Shared\Utils\Hash;

class UpdateUserPasswordService
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(NewPasswordDTO $passwordDTO): void
    {
        $user = UsersValidations::validateUserExistsById(
            $passwordDTO->userId,
            $this->usersRepository
        );

        UsersValidations::checkIfPasswordsMatch(
            $passwordDTO->currentPassword,
            $user->password
        );

        $passwordDTO->newPassword = Hash::generateHash($passwordDTO->newPassword);

        $this->usersRepository->saveNewPassword(
            $passwordDTO->userId,
            $passwordDTO->newPassword,
        );

        PolicyCache::invalidatePolicy($passwordDTO->userId);
    }
}
