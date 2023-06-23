<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\DTO\GeneralDataUpdateDTO;

interface UsersRepositoryInterface
{
    public function findById(string $id): ?object;
    public function findByEmail(string $email): ?object;
    public function findByPhone(string $phone): ?object;
    public function create(UserDTO $userDTO, bool $usePerson = false);
    public function save(UserDTO $userDTO);
    public function saveInMembers(GeneralDataUpdateDTO $generalDataUpdateDTO): object;
    public function saveProfiles(string $userId, array $profiles): void;
    public function saveModules(string $userId, array $modules): void;
    public function saveNewPassword(string $userId, string $password);
    public function saveStatus(string $userId, bool $status);
    public function saveAvatar(string $userId, string $imageId);
}
