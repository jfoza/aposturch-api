<?php

namespace App\Modules\Store\Departments\Contracts;

interface FindByDepartmentIdServiceInterface
{
    public function execute(string $id): object;
}
