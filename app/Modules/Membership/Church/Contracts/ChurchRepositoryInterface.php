<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ChurchRepositoryInterface
{
    public function findAll(ChurchFiltersDTO $churchFiltersDTO): LengthAwarePaginator|Collection;
    public function findByMemberId(string $memberId, bool $isMany = false): mixed;
    public function findById(string $churchId): object|null;
    public function findByIds(array $churchIds): mixed;
    public function findByIdWithMembers(string $churchId): object|null;
    public function findByUniqueName(string $uniqueName): object|null;
    public function create(ChurchDTO $churchDTO): Church|Collection;
    public function save(ChurchDTO $churchDTO): Church;
    public function saveImages(string $churchId, array $images): void;
    public function remove(string $churchId): void;
}
