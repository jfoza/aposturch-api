<?php

namespace App\Features\Users\Users\Infra\Repositories;

use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Infra\Models\User;

class UsersRepository implements UsersRepositoryInterface
{
    public function findById(string $id): mixed
    {
        return User::where(User::ID, $id)->first();
    }

    public function findByEmail(string $email): mixed
    {
        return User::where(User::EMAIL, $email)->first();
    }

    public function create(UserDTO $userDTO, bool $customerUser = false)
    {
        $create = [
            User::NAME      => $userDTO->name,
            User::EMAIL     => $userDTO->email,
            User::PASSWORD  => $userDTO->newPasswordGenerationsDTO->passwordEncrypt,
            User::ACTIVE    => $userDTO->active,
        ];

        if($customerUser) {
            $create[User::PERSON_ID] = $userDTO->personId;
        }

        return User::create($create);
    }

    public function save(UserDTO $userDTO)
    {
        $saved = [
            User::ID     => $userDTO->id,
            User::NAME   => $userDTO->name,
            User::EMAIL  => $userDTO->email,
            User::ACTIVE => $userDTO->active,
        ];

        User::where(User::ID, $userDTO->id)
            ->update($saved);

        return (object) $saved;
    }

    public function saveProfiles(string $userId, array $profiles): void {
        User::find($userId)->profile()->sync($profiles);
    }

    public function saveNewPassword(string $userId, string $password) {
        return User::where(User::ID, $userId)
            ->update([User::PASSWORD => $password]);
    }
}
