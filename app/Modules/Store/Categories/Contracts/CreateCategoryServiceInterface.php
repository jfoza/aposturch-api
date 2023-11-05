<?php

namespace App\Modules\Store\Categories\Contracts;

use App\Modules\Store\Categories\DTO\CategoriesDTO;

interface CreateCategoryServiceInterface
{
    public function execute(CategoriesDTO $categoriesDTO): object;
}
