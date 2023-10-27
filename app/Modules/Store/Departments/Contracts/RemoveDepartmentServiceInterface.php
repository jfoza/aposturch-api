<?php

namespace App\Modules\Store\Departments\Contracts;

interface RemoveDepartmentServiceInterface
{
    public function execute(string $id): void;
}
