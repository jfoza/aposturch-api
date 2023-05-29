<?php

namespace App\Modules\Membership\Church\Repositories;

use App\Features\Base\Traits\BuilderTrait;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Modules\Membership\Church\Models\Church;
use App\Modules\Membership\Members\Models\Member;
use App\Modules\Membership\MemberTypes\Models\MemberType;
use App\Shared\Enums\MemberTypesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ChurchRepository implements ChurchRepositoryInterface
{
    use BuilderTrait;

    public function findAll(ChurchFiltersDTO $churchFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = Church::with(['city'])
            ->when(
                isset($churchFiltersDTO->name),
                fn($q) => $q->where(Church::tableField(Church::NAME), 'ilike', "%{$churchFiltersDTO->name}%"))
            ->when(
                isset($churchFiltersDTO->cityId),
                fn($q) => $q->where(Church::tableField(Church::CITY_ID), $churchFiltersDTO->cityId)
            )
            ->when(
                isset($churchFiltersDTO->churchIds),
                fn($q) => $q->whereIn(Church::tableField(Church::ID), $churchFiltersDTO->churchIds)
            );

        return $this->paginateOrGet($builder, $churchFiltersDTO->paginationOrder);
    }

    public function findByMemberId(string $memberId, bool $isMany = false): mixed
    {
        $builder = Church::whereRelation(
            'member',
            Member::tableField(Member::ID),
            $memberId
        );

        return $isMany ? $builder->get()->toArray() : $builder->first();
    }

    public function findById(string $churchId): object|null
    {
        return Church::with([
                'imagesChurch',
                'city',
                'member' => fn($member) => $member->with(['user', 'memberType'])
            ])
            ->find($churchId);
    }

    public function findByIds(array $churchIds): mixed
    {
        return Church::whereIn(Church::ID, $churchIds)->get();
    }

    public function findByIdWithMembers(string $churchId): object|null
    {
        return Church::with([
                'imagesChurch',
                'city',
                'member' => function($member) {
                    return $member->with(['user', 'memberType']);
                }
            ])
            ->find($churchId);
    }

    public function findByUniqueName(string $uniqueName): object|null
    {
        return Church::with(['imagesChurch', 'city'])
            ->where(Church::UNIQUE_NAME, $uniqueName)
            ->first();
    }

    public function create(ChurchDTO $churchDTO): Church|Collection
    {
        return Church::create([
            Church::NAME           => $churchDTO->name,
            Church::UNIQUE_NAME    => $churchDTO->uniqueName,
            Church::PHONE          => $churchDTO->phone,
            Church::EMAIL          => $churchDTO->email,
            Church::YOUTUBE        => $churchDTO->youtube,
            Church::FACEBOOK       => $churchDTO->facebook,
            Church::INSTAGRAM      => $churchDTO->instagram,
            Church::ZIP_CODE       => $churchDTO->zipCode,
            Church::ADDRESS        => $churchDTO->address,
            Church::NUMBER_ADDRESS => $churchDTO->numberAddress,
            Church::COMPLEMENT     => $churchDTO->complement,
            Church::DISTRICT       => $churchDTO->district,
            Church::UF             => $churchDTO->uf,
            Church::CITY_ID        => $churchDTO->cityId,
            Church::ACTIVE         => $churchDTO->active,
        ]);
    }

    public function save(ChurchDTO $churchDTO): Church
    {
        $update = [
            Church::ID => $churchDTO->id,
            Church::NAME => $churchDTO->name,
            Church::UNIQUE_NAME => $churchDTO->uniqueName,
            Church::PHONE => $churchDTO->phone,
            Church::EMAIL => $churchDTO->email,
            Church::YOUTUBE => $churchDTO->youtube,
            Church::FACEBOOK => $churchDTO->facebook,
            Church::INSTAGRAM => $churchDTO->instagram,
            Church::ZIP_CODE => $churchDTO->zipCode,
            Church::ADDRESS => $churchDTO->address,
            Church::NUMBER_ADDRESS => $churchDTO->numberAddress,
            Church::COMPLEMENT => $churchDTO->complement,
            Church::DISTRICT => $churchDTO->district,
            Church::UF => $churchDTO->uf,
            Church::CITY_ID => $churchDTO->cityId,
            Church::ACTIVE => $churchDTO->active,
        ];

        Church::where(Church::ID, $churchDTO->id)->update($update);

        return Church::make($update);
    }

    public function saveImages(string $churchId, array $images): void
    {
        Church::find($churchId)->imagesChurch()->sync($images);
    }

    public function remove(string $churchId): void
    {
        Church::where(Church::ID, $churchId)->delete();
    }
}
