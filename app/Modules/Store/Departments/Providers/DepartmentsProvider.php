<?php

namespace App\Modules\Store\Departments\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Contracts\CreateDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\FindAllDepartmentsServiceInterface;
use App\Modules\Store\Departments\Contracts\FindByDepartmentIdServiceInterface;
use App\Modules\Store\Departments\Contracts\RemoveDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\UpdateDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\UpdateStatusDepartmentsServiceInterface;
use App\Modules\Store\Departments\Repositories\DepartmentsRepository;
use App\Modules\Store\Departments\Services\CreateDepartmentService;
use App\Modules\Store\Departments\Services\FindAllDepartmentsService;
use App\Modules\Store\Departments\Services\FindByDepartmentIdService;
use App\Modules\Store\Departments\Services\RemoveDepartmentService;
use App\Modules\Store\Departments\Services\UpdateDepartmentService;
use App\Modules\Store\Departments\Services\UpdateStatusDepartmentsService;

class DepartmentsProvider extends AbstractServiceProvider
{
    public array $bindings = [
        DepartmentsRepositoryInterface::class => DepartmentsRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllDepartmentsServiceInterface::class,
            FindAllDepartmentsService::class,
        );

        $this->bind(
            FindByDepartmentIdServiceInterface::class,
            FindByDepartmentIdService::class,
        );

        $this->bind(
            CreateDepartmentServiceInterface::class,
            CreateDepartmentService::class,
        );

        $this->bind(
            UpdateDepartmentServiceInterface::class,
            UpdateDepartmentService::class,
        );

        $this->bind(
            RemoveDepartmentServiceInterface::class,
            RemoveDepartmentService::class,
        );

        $this->bind(
            UpdateStatusDepartmentsServiceInterface::class,
            UpdateStatusDepartmentsService::class,
        );
    }
}
