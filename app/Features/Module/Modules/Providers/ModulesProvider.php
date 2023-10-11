<?php

namespace App\Features\Module\Modules\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Features\Module\Modules\Contracts\FindAllModulesByUserLoggedServiceInterface;
use App\Features\Module\Modules\Contracts\ModulesRepositoryInterface;
use App\Features\Module\Modules\Repositories\ModulesRepository;
use App\Features\Module\Modules\Services\FindAllModulesByUserLoggedService;

class ModulesProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ModulesRepositoryInterface::class => ModulesRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllModulesByUserLoggedServiceInterface::class,
            FindAllModulesByUserLoggedService::class
        );
    }
}
