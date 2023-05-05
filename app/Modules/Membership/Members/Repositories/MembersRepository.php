<?php

namespace App\Modules\Membership\Members\Repositories;

use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Models\Member;
use Illuminate\Database\Eloquent\Collection;

class MembersRepository implements MembersRepositoryInterface
{
    public function findAll()
    {
        // TODO: Implement findAll() method.
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
