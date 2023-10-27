<?php

namespace App\Modules\Store\Categories\Contracts;

use Illuminate\Support\Collection;

interface UpdateStatusCategoriesServiceInterface
{
    public function execute(array $categoriesId): Collection;
}
