<?php

namespace App\Features\Users\Users\Services;

use App\Shared\Cache\PolicyCache;
use App\Exceptions\AppException;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\Services\Utils\HashService;
use App\Features\Users\Users\Services\Utils\UsersValidationsService;

class UpdateUserPasswordService
{
    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(PasswordDTO $passwordDTO): void
    {
        $user = UsersValidationsService::validateUserExistsById(
            $passwordDTO->userId,
            $this->usersRepository
        );

        UsersValidationsService::checkIfPasswordsMatch(
            $passwordDTO->currentPassword,
            $user->password
        );

        $passwordDTO->newPassword = HashService::generateHash($passwordDTO->newPassword);

        $this->usersRepository->saveNewPassword(
            $passwordDTO->userId,
            $passwordDTO->newPassword,
        );

        PolicyCache::invalidatePolicy($passwordDTO->userId);
    }
}
