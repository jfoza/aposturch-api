<?php

namespace App\Modules\Store\Products\Contracts;

interface UpdateStatusProductsServiceInterface
{
    public function execute(array $productsId): object;
}
