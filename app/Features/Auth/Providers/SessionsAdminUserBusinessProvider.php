<?php

namespace App\Features\Auth\Providers;

use App\Features\Auth\Business\SessionsAdminUserBusiness;
use App\Features\Auth\Contracts\SessionsAdminUserBusinessInterface;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Sessions\Infra\Repositories\SessionsRepository;
use Illuminate\Support\ServiceProvider;

class SessionsAdminUserBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        SessionsRepositoryInterface::class => SessionsRepository::class,
        RulesRepositoryInterface::class => RulesRepository::class,
        SessionsAdminUserBusinessInterface::class => SessionsAdminUserBusiness::class,
    ];

    public function register()
    {}
}
