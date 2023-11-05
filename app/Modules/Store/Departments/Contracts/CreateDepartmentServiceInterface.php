<?php

namespace App\Modules\Store\Departments\Contracts;

use App\Modules\Store\Departments\DTO\DepartmentsDTO;

interface CreateDepartmentServiceInterface
{
    public function execute(DepartmentsDTO $departmentsDTO): object;
}
