<?php

namespace App\Modules\Store\Products\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Repositories\ProductsRepository;

class ProductsProviders extends AbstractServiceProvider
{
    public array $bindings = [
        ProductsRepositoryInterface::class => ProductsRepository::class,
    ];
}
