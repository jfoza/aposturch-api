<?php

namespace App\Modules\Store\Departments\Contracts;

use Illuminate\Support\Collection;

interface UpdateStatusDepartmentsServiceInterface
{
    public function execute(array $departmentsId): Collection;
}
