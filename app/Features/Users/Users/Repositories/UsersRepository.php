<?php

namespace App\Features\Users\Users\Repositories;

use App\Features\Users\UserChurch\Infra\Models\UserChurch;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as CollectionSupport;

class UsersRepository implements UsersRepositoryInterface
{
    public function findAllByChurch(UserFiltersDTO $userFiltersDTO): LengthAwarePaginator|CollectionSupport
    {
        return User::with(['person'])
        ->select(
            User::tableField(User::ID),
            User::tableField(User::PERSON_ID),
            User::tableField(User::NAME),
            User::tableField(User::EMAIL),
        )
        ->when(isset($userFiltersDTO->name),
            function($q) use($userFiltersDTO) {
                return $q->where(
                    User::tableField(User::NAME),
                    'ilike',
                    "%{$userFiltersDTO->name}%"
                );
            }
        )
        ->whereRelation(
            'church',
            Church::tableField(Church::ID),
            $userFiltersDTO->churchId
        )
        ->paginate($userFiltersDTO->paginationOrder->getPerPage());
    }

    public function findById(string $id): ?object
    {
        return User::with(['church'])
            ->where(User::ID, $id)
            ->first();
    }

    public function findByEmail(string $email): ?object
    {
        return User::with(['profile', 'module'])
            ->where(User::EMAIL, $email)
            ->first();
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

    public function removeChurchRelationship(string $userId, string $churchId): void
    {
        UserChurch::where([
            UserChurch::USER_ID => $userId,
            UserChurch::CHURCH_ID => $churchId,
        ])->delete();
    }
}
