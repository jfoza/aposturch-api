<?php

namespace App\Modules\Store\Subcategories\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Subcategories\Contracts\CreateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\FindAllSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Contracts\FindBySubcategoryIdServiceInterface;
use App\Modules\Store\Subcategories\Contracts\RemoveSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateStatusSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Repositories\SubcategoriesRepository;
use App\Modules\Store\Subcategories\Services\CreateSubcategoryService;
use App\Modules\Store\Subcategories\Services\FindAllSubcategoriesService;
use App\Modules\Store\Subcategories\Services\FindBySubcategoryIdService;
use App\Modules\Store\Subcategories\Services\RemoveSubcategoryService;
use App\Modules\Store\Subcategories\Services\UpdateStatusSubcategoriesService;
use App\Modules\Store\Subcategories\Services\UpdateSubcategoryService;

class SubcategoriesProvider extends AbstractServiceProvider
{
    public array $bindings = [
        SubcategoriesRepositoryInterface::class => SubcategoriesRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllSubcategoriesServiceInterface::class,
            FindAllSubcategoriesService::class,
        );

        $this->bind(
            FindBySubcategoryIdServiceInterface::class,
            FindBySubcategoryIdService::class,
        );

        $this->bind(
            CreateSubcategoryServiceInterface::class,
            CreateSubcategoryService::class,
        );

        $this->bind(
            UpdateSubcategoryServiceInterface::class,
            UpdateSubcategoryService::class,
        );

        $this->bind(
            UpdateStatusSubcategoriesServiceInterface::class,
            UpdateStatusSubcategoriesService::class,
        );

        $this->bind(
            RemoveSubcategoryServiceInterface::class,
            RemoveSubcategoryService::class,
        );
    }
}
