<?php

namespace App\Modules\Store\Categories\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CreateCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\FindAllCategoriesServiceInterface;
use App\Modules\Store\Categories\Contracts\FindByCategoryIdServiceInterface;
use App\Modules\Store\Categories\Contracts\RemoveCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\UpdateCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\UpdateStatusCategoryServiceInterface;
use App\Modules\Store\Categories\Repositories\CategoriesRepository;
use App\Modules\Store\Categories\Services\CreateCategoryService;
use App\Modules\Store\Categories\Services\FindAllCategoriesService;
use App\Modules\Store\Categories\Services\FindByCategoryIdService;
use App\Modules\Store\Categories\Services\RemoveCategoryService;
use App\Modules\Store\Categories\Services\UpdateCategoryService;
use App\Modules\Store\Categories\Services\UpdateStatusCategoryService;

class CategoriesProvider extends AbstractServiceProvider
{
    public array $bindings = [
        CategoriesRepositoryInterface::class => CategoriesRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllCategoriesServiceInterface::class,
            FindAllCategoriesService::class,
        );

        $this->bind(
            FindByCategoryIdServiceInterface::class,
            FindByCategoryIdService::class,
        );

        $this->bind(
            CreateCategoryServiceInterface::class,
            CreateCategoryService::class,
        );

        $this->bind(
            UpdateCategoryServiceInterface::class,
            UpdateCategoryService::class,
        );

        $this->bind(
            RemoveCategoryServiceInterface::class,
            RemoveCategoryService::class,
        );

        $this->bind(
            UpdateStatusCategoryServiceInterface::class,
            UpdateStatusCategoryService::class,
        );
    }
}
