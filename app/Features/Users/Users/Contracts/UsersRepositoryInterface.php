<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\Users\Users\DTO\UserDTO;

interface UsersRepositoryInterface
{
    public function findById(string $id): mixed;
    public function findByEmail(string $email): mixed;
    public function create(UserDTO $userDTO, bool $customerUser = false);
    public function save(UserDTO $userDTO);
    public function saveProfiles(string $userId, array $profiles): void;
    public function saveNewPassword(string $userId, string $password);
}
