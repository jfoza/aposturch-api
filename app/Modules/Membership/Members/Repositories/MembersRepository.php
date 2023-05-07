<?php

namespace App\Modules\Membership\Members\Repositories;

use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\MemberTypes\Models\MemberType;
use App\Shared\Enums\MemberTypesEnum;
use Illuminate\Database\Eloquent\Collection;

class MembersRepository implements MembersRepositoryInterface
{
    public function findAll()
    {
        // TODO: Implement findAll() method.
    }

    public function findAllResponsible()
    {
        return $this->getMemberUserActive()
            ->with(['user', 'memberType', 'church'])
            ->withCount('church')
            ->has('church', '<', 4)
            ->whereRelation(
                'memberType',
                MemberType::UNIQUE_NAME,
                MemberTypesEnum::RESPONSIBLE
            )
            ->whereHas(
                'user',
                fn($q) => $q->whereRelation(
                    'profile',
                    Profile::UNIQUE_NAME,
                    ProfileUniqueNameEnum::ADMIN_CHURCH
                )
            )
            ->get();
    }

    public function findById(string $id)
    {
        return $this->getMemberUserActive()->where(Member::ID, $id)->first();
    }

    public function findByIds(array $ids): mixed
    {
        return $this->getMemberUserActive()
            ->with(['memberType', 'user.profile'])
            ->whereIn(Member::ID, $ids)
            ->get();
    }

    public function create()
    {
        // TODO: Implement create() method.
    }

    public function save()
    {
        // TODO: Implement save() method.
    }

    public function remove(string $id)
    {
        // TODO: Implement remove() method.
    }

    private function getMemberUserActive()
    {
        return Member::whereRelation(
            'user',
            User::ACTIVE,
            '=',
            true
        );
    }
}
