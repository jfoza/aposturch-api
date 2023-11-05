<?php

namespace App\Features\General\UniqueCodePrefixes\Repositories;

use App\Base\Traits\BuilderTrait;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use App\Features\General\UniqueCodePrefixes\Models\UniqueCodePrefix;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UniqueCodePrefixesRepository implements UniqueCodePrefixesRepositoryInterface
{
    use BuilderTrait;

    public function findAll(UniqueCodePrefixesFiltersDTO $uniqueCodePrefixesFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQuery()
            ->when(
                isset($uniqueCodePrefixesFiltersDTO->prefix),
                fn($q) => $q->where(
                    UniqueCodePrefix::PREFIX,
                    'ilike',
                    "%$uniqueCodePrefixesFiltersDTO->prefix%"
                )
            )
            ->orderBy(UniqueCodePrefix::CREATED_AT, 'desc');

        return $this->paginateOrGet(
            $builder,
            $uniqueCodePrefixesFiltersDTO->paginationOrder
        );
    }

    public function findById(string $id): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(UniqueCodePrefix::ID, $id)
            ->first();
    }

    public function findByPrefix(string $prefix): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(UniqueCodePrefix::PREFIX, $prefix)
            ->first();
    }

    public function create(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object
    {
        return UniqueCodePrefix::create([
            UniqueCodePrefix::PREFIX => $uniqueCodePrefixesDTO->prefix,
            UniqueCodePrefix::ACTIVE => $uniqueCodePrefixesDTO->active,
        ]);
    }

    public function save(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object
    {
        $update = [
            UniqueCodePrefix::PREFIX => $uniqueCodePrefixesDTO->prefix,
            UniqueCodePrefix::ACTIVE => $uniqueCodePrefixesDTO->active,
        ];

        UniqueCodePrefix::where(UniqueCodePrefix::ID, $uniqueCodePrefixesDTO->id)->update($update);

        return (object) ($update);
    }

    public function remove(string $id): void
    {
        UniqueCodePrefix::where(UniqueCodePrefix::ID, $id)->delete();
    }

    private function getBaseQuery()
    {
        return UniqueCodePrefix::select(
            UniqueCodePrefix::ID,
            UniqueCodePrefix::PREFIX,
            UniqueCodePrefix::ACTIVE,
            UniqueCodePrefix::CREATED_AT,
        );
    }
}
