<?php

namespace App\Modules\Store\Subcategories\Contracts;

interface FindBySubcategoryIdServiceInterface
{
    public function execute(string $id): object;
}
