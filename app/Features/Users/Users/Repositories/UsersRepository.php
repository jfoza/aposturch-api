<?php

namespace App\Features\Users\Users\Repositories;

use App\Features\Persons\Infra\Models\Person;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Models\User;

class UsersRepository implements UsersRepositoryInterface
{
    public function findById(string $id): ?object
    {
        return User::where(User::ID, $id)->first();
    }

    public function findByEmail(string $email): ?object
    {
        return User::with(['adminUser', 'profile', 'module', 'member'])
            ->where(User::EMAIL, $email)
            ->first();
    }

    public function findByPhone(string $phone): ?object
    {
        return User::with(['adminUser', 'profile', 'module'])
            ->whereRelation(
                'person',
                Person::PHONE,
                $phone
            )
            ->first();
    }

    public function create(UserDTO $userDTO, bool $usePerson = false)
    {
        $create = [
            User::NAME      => $userDTO->name,
            User::EMAIL     => $userDTO->email,
            User::PASSWORD  => $userDTO->newPasswordGenerationsDTO->passwordEncrypt,
            User::ACTIVE    => $userDTO->active,
        ];

        if($usePerson) {
            $create[User::PERSON_ID] = $userDTO->personId;
        }

        return User::create($create);
    }

    public function save(UserDTO $userDTO): object
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

    public function saveInMembers(UserDTO $userDTO): object
    {
        $saved = [
            User::ID     => $userDTO->id,
            User::NAME   => $userDTO->name,
            User::EMAIL  => $userDTO->email,
        ];

        User::where(User::ID, $userDTO->id)->update($saved);

        return (object) $saved;
    }

    public function saveProfiles(string $userId, array $profiles): void {
        User::find($userId)->profile()->sync($profiles);
    }

    public function saveModules(string $userId, array $modules): void {
        User::find($userId)->module()->sync($modules);
    }

    public function saveNewPassword(string $userId, string $password) {
        return User::where(User::ID, $userId)
            ->update([User::PASSWORD => $password]);
    }

    public function saveStatus(string $userId, bool $status)
    {
        return User::where(User::ID, $userId)->update([User::ACTIVE => $status]);
    }

    public function saveAvatar(string $userId, string $imageId)
    {
        return User::where(User::ID, $userId)->update([User::AVATAR_ID => $imageId]);
    }
}
