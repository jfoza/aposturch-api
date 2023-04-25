<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ChurchRepositoryInterface
{
    public function findAll(ChurchFiltersDTO $churchFiltersDTO);
    public function findById(string $churchId, bool $listMembers = false): object|null;
    public function findByUniqueName(string $uniqueName): object|null;
    public function create(ChurchDTO $churchDTO): Church|Collection;
    public function save(ChurchDTO $churchDTO): Church;
    public function remove(string $churchId): void;
    public function saveImages(string $churchId, array $images);
    public function saveResponsible(string $churchId, array $usersId);
}
