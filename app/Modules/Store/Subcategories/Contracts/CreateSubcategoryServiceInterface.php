<?php

namespace App\Modules\Store\Subcategories\Contracts;

use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;

interface CreateSubcategoryServiceInterface
{
    public function execute(SubcategoriesDTO $subcategoriesDTO): object;
}
