<?php

namespace App\Features\Users\Users\Contracts;

use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UsersRepositoryInterface
{
    public function findAll(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): mixed;
    public function findByEmail(string $email): mixed;
    public function create(UserDTO $userDTO, bool $customerUser = false);
    public function save(UserDTO $userDTO);
    public function saveProfiles(string $userId, array $profiles): void;
    public function saveNewPassword(string $userId, string $password);
    public function removeChurchRelationship(string $userId): void;
}
