<?php

namespace App\Modules\Store\Categories\Contracts;

interface RemoveCategoryServiceInterface
{
    public function execute(string $id): void;
}
