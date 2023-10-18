<?php

namespace App\Modules\Store\Subcategories\Contracts;

use Illuminate\Support\Collection;

interface UpdateStatusSubcategoriesServiceInterface
{
    public function execute(array $subcategoriesId): Collection;
}
