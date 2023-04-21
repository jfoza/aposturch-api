<?php

namespace App\Features\Auth\Providers;

use App\Features\Auth\Contracts\AdminUsersAuthServiceInterface;
use App\Features\Auth\Services\AdminUsersAuthService;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Infra\Repositories\SessionsRepository;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public array $bindings = [
        SessionsRepositoryInterface::class => SessionsRepository::class,
        RulesRepositoryInterface::class => RulesRepository::class,

        AdminUsersAuthServiceInterface::class => AdminUsersAuthService::class,
    ];
}
