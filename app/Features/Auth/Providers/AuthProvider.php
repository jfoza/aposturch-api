<?php

namespace App\Features\Auth\Providers;

use App\Features\Auth\Business\AuthBusiness;
use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\Services\AuthGenerateService;
use App\Features\Auth\Services\ShowAuthUserService;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use Illuminate\Support\ServiceProvider;

class AuthProvider extends ServiceProvider
{
    public array $bindings = [
        RulesRepositoryInterface::class => RulesRepository::class,

        AuthBusinessInterface::class => AuthBusiness::class,

        ShowAuthUserServiceInterface::class  => ShowAuthUserService::class,
        AuthGenerateServiceInterface::class  => AuthGenerateService::class,
    ];
}
