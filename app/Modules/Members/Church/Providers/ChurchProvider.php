<?php

namespace App\Modules\Members\Church\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Base\Traits\PolicyGenerationTrait;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Members\Church\Contracts\ChurchUploadImageServiceInterface;
use App\Modules\Members\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Members\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Members\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Members\Church\Repositories\ChurchRepository;
use App\Modules\Members\Church\Services\ChurchUploadImageService;
use App\Modules\Members\Church\Services\CreateChurchService;
use App\Modules\Members\Church\Services\FindAllChurchesService;
use App\Modules\Members\Church\Services\RemoveChurchService;
use App\Modules\Members\Church\Services\ShowByChurchIdService;
use App\Modules\Members\Church\Services\UpdateChurchService;

class ChurchProvider extends AbstractServiceProvider
{
    use PolicyGenerationTrait;

    public array $bindings = [
        ChurchRepositoryInterface::class => ChurchRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllChurchesServiceInterface::class,
            FindAllChurchesService::class
        );

        $this->bind(
            ShowByChurchIdServiceInterface::class,
            ShowByChurchIdService::class
        );

        $this->bind(
            CreateChurchServiceInterface::class,
            CreateChurchService::class
        );

        $this->bind(
            UpdateChurchServiceInterface::class,
            UpdateChurchService::class
        );

        $this->bind(
            RemoveChurchServiceInterface::class,
            RemoveChurchService::class
        );

        $this->bind(
            ChurchUploadImageServiceInterface::class,
            ChurchUploadImageService::class
        );
    }
}
