<?php

namespace App\Modules\Store\Subcategories\Contracts;

interface RemoveSubcategoryServiceInterface
{
    public function execute(string $id): void;
}
