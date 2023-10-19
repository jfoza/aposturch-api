<?php

namespace App\Modules\Store\Products\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Products\Contracts\FindAllProductsServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\FindAllProductsService;

class ProductsProviders extends AbstractServiceProvider
{
    public array $bindings = [
        ProductsRepositoryInterface::class => ProductsRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllProductsServiceInterface::class,
            FindAllProductsService::class,
        );
    }
}
