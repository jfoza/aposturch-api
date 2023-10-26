<?php

namespace App\Features\General\UniqueCodePrefixes\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Features\General\UniqueCodePrefixes\Contracts\CreateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\FindAllUniqueCodePrefixesServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\FindByUniqueCodePrefixIdServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\RemoveUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodePrefixesRepositoryInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UpdateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Repositories\UniqueCodePrefixesRepository;
use App\Features\General\UniqueCodePrefixes\Services\CreateUniqueCodePrefixService;
use App\Features\General\UniqueCodePrefixes\Services\FindAllUniqueCodePrefixesService;
use App\Features\General\UniqueCodePrefixes\Services\FindByUniqueCodePrefixIdService;
use App\Features\General\UniqueCodePrefixes\Services\RemoveUniqueCodePrefixService;
use App\Features\General\UniqueCodePrefixes\Services\UpdateUniqueCodePrefixService;

class UniqueCodePrefixesProvider extends AbstractServiceProvider
{
    public array $bindings = [
        UniqueCodePrefixesRepositoryInterface::class => UniqueCodePrefixesRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllUniqueCodePrefixesServiceInterface::class,
            FindAllUniqueCodePrefixesService::class,
        );

        $this->bind(
            FindByUniqueCodePrefixIdServiceInterface::class,
            FindByUniqueCodePrefixIdService::class,
        );

        $this->bind(
            CreateUniqueCodePrefixServiceInterface::class,
            CreateUniqueCodePrefixService::class,
        );

        $this->bind(
            UpdateUniqueCodePrefixServiceInterface::class,
            UpdateUniqueCodePrefixService::class,
        );

        $this->bind(
            RemoveUniqueCodePrefixServiceInterface::class,
            RemoveUniqueCodePrefixService::class,
        );
    }
}
