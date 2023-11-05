<?php

namespace App\Features\Users\Sessions\Providers;

use App\Features\Users\Sessions\Contracts\CreateSessionDataServiceInterface;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Repositories\SessionsRepository;
use App\Features\Users\Sessions\Services\CreateSessionDataService;
use Illuminate\Support\ServiceProvider;

class SessionsProvider extends ServiceProvider
{
    public array $bindings = [
        SessionsRepositoryInterface::class => SessionsRepository::class,

        CreateSessionDataServiceInterface::class => CreateSessionDataService::class,
    ];
}
