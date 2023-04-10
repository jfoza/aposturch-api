<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Models\Church;

interface ChurchRepositoryInterface
{
    public function findAll(ChurchFiltersDTO $churchFiltersDTO);
    public function findById(string $churchId, bool $listMembers = false): mixed;
    public function create(ChurchDTO $churchDTO): Church;
    public function save(ChurchDTO $churchDTO): Church;
    public function remove(string $churchId): void;
}
