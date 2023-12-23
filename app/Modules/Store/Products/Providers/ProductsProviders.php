<?php

namespace App\Modules\Store\Products\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Products\Contracts\CreateProductServiceInterface;
use App\Modules\Store\Products\Contracts\FindAllProductsServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductUniqueCodeGeneratorServiceInterface;
use App\Modules\Store\Products\Contracts\ShowByProductIdServiceInterface;
use App\Modules\Store\Products\Contracts\ShowByProductUniqueNameServiceInterface;
use App\Modules\Store\Products\Contracts\UpdateProductServiceInterface;
use App\Modules\Store\Products\Contracts\UpdateStatusProductsServiceInterface;
use App\Modules\Store\Products\Repositories\ProductsPersistenceRepository;
use App\Modules\Store\Products\Repositories\ProductsRepository;
use App\Modules\Store\Products\Services\CreateProductService;
use App\Modules\Store\Products\Services\FindAllProductsService;
use App\Modules\Store\Products\Services\ProductUniqueCodeGeneratorService;
use App\Modules\Store\Products\Services\ShowByProductIdService;
use App\Modules\Store\Products\Services\ShowByProductUniqueNameService;
use App\Modules\Store\Products\Services\UpdateProductService;
use App\Modules\Store\Products\Services\UpdateStatusProductsService;

class ProductsProviders extends AbstractServiceProvider
{
    public array $bindings = [
        ProductsRepositoryInterface::class            => ProductsRepository::class,
        ProductsPersistenceRepositoryInterface::class => ProductsPersistenceRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllProductsServiceInterface::class,
            FindAllProductsService::class,
        );

        $this->bind(
            ShowByProductIdServiceInterface::class,
            ShowByProductIdService::class,
        );

        $this->bind(
            ShowByProductUniqueNameServiceInterface::class,
            ShowByProductUniqueNameService::class,
        );

        $this->bind(
            CreateProductServiceInterface::class,
            CreateProductService::class,
        );

        $this->bind(
            UpdateProductServiceInterface::class,
            UpdateProductService::class,
        );

        $this->bind(
            UpdateStatusProductsServiceInterface::class,
            UpdateStatusProductsService::class,
        );

        $this->bind(
            ProductUniqueCodeGeneratorServiceInterface::class,
            ProductUniqueCodeGeneratorService::class,
        );
    }
}
