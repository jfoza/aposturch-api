<?php

namespace App\Modules\Store\Departments\Contracts;

use App\Modules\Store\Departments\DTO\DepartmentsDTO;

interface UpdateDepartmentServiceInterface
{
    public function execute(DepartmentsDTO $departmentsDTO): object;
}
