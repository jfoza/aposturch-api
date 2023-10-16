<?php

namespace App\Modules\Store\Categories\Contracts;

use Illuminate\Support\Collection;

interface UpdateStatusCategoryServiceInterface
{
    public function execute(array $categoriesId): Collection;
}
