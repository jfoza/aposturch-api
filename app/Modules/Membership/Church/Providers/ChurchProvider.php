<?php

namespace App\Modules\Membership\Church\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Base\Traits\PolicyGenerationTrait;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ChurchUploadImageServiceInterface;
use App\Modules\Membership\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Membership\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Membership\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Membership\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Membership\Church\Contracts\ShowByChurchUniqueNameServiceInterface;
use App\Modules\Membership\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Membership\Church\Repositories\ChurchRepository;
use App\Modules\Membership\Church\Services\ChurchUploadImageService;
use App\Modules\Membership\Church\Services\CreateChurchService;
use App\Modules\Membership\Church\Services\FindAllChurchesService;
use App\Modules\Membership\Church\Services\RemoveChurchService;
use App\Modules\Membership\Church\Services\ShowByChurchIdService;
use App\Modules\Membership\Church\Services\ShowByChurchUniqueNameService;
use App\Modules\Membership\Church\Services\UpdateChurchService;

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
            ShowByChurchUniqueNameServiceInterface::class,
            ShowByChurchUniqueNameService::class
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
