<?php

namespace App\Modules\Store\Departments\Contracts;

use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllDepartmentsServiceInterface
{
    public function execute(DepartmentsFiltersDTO $departmentsFiltersDTO): LengthAwarePaginator|Collection;
}
