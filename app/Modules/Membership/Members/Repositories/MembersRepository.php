<?php

namespace App\Modules\Membership\Members\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Features\Users\Profiles\Models\Profile;
use App\Features\Users\Users\Models\User;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\DTO\MemberDTO;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\Members\Traits\MembersListsTrait;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class MembersRepository implements MembersRepositoryInterface
{
    use BuilderTrait;
    use MembersListsTrait;

    public function findAll(MembersFiltersDTO $membersFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->baseQueryBuilderFilters($membersFiltersDTO)
            ->orderBy(
                $membersFiltersDTO->paginationOrder->defineCustomColumnName(Profile::tableField(Profile::ID)),
                $membersFiltersDTO->paginationOrder->getColumnOrder()
            );

        return $this->paginateOrGet($builder, $membersFiltersDTO->paginationOrder);
    }

    public function findById(string $id): ?object
    {
        return Member::with(['user'])
            ->where(Member::ID, $id)
            ->first();
    }

    public function findByUserId(string $id): ?object
    {
        return $this->getBaseQueryBuilder()
            ->where(User::tableField(User::ID), $id)
            ->first();
    }

    public function findByIds(array $ids): Collection
    {
        return $this->getBaseQueryBuilder()
            ->whereIn(Member::tableField(Member::ID), $ids)
            ->get();
    }

    public function findOneByFilters(string $userId, MembersFiltersDTO $membersFiltersDTO): ?object
    {
        return $this->getBaseQueryBuilder()
            ->when(
                isset($membersFiltersDTO->profileUniqueName),
                fn($q) => $q->whereIn(Profile::tableField(Profile::UNIQUE_NAME), $membersFiltersDTO->profileUniqueName)
            )
            ->when(
                isset($membersFiltersDTO->churchIds),
                fn($q) => $q->whereHas(
                    'church',
                    fn($c) => $c->whereIn(Church::tableField(Church::ID), $membersFiltersDTO->churchIds)
                )
            )
            ->where(User::tableField(User::ID), $userId)
            ->first();
    }

    public function create(MemberDTO $memberDTO): object
    {
        return Member::create([
            Member::USER_ID => $memberDTO->userId
        ]);
    }

    public function saveMembers(string $memberId, array $churches): void
    {
        Member::find($memberId)->church()->sync($churches);
    }
}
