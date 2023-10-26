<?php

namespace App\Features\General\UniqueCodePrefixes\Contracts;

use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface UniqueCodePrefixesRepositoryInterface
{
    public function findAll(UniqueCodePrefixesFiltersDTO $uniqueCodePrefixesFiltersDTO): LengthAwarePaginator|Collection;
    public function findById(string $id): ?object;
    public function findByPrefix(string $prefix): ?object;
    public function create(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object;
    public function save(UniqueCodePrefixesDTO $uniqueCodePrefixesDTO): object;
    public function remove(string $id): void;
}
