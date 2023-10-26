<?php

namespace App\Modules\Store\Products\Contracts;

use App\Modules\Store\Products\DTO\ProductsDTO;

interface CreateProductServiceInterface
{
    public function execute(ProductsDTO $productsDTO): object;
}
