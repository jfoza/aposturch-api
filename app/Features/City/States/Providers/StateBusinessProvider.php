<?php

namespace App\Features\City\States\Providers;

use App\Features\City\States\Business\StateBusiness;
use App\Features\City\States\Contracts\StateBusinessInterface;
use App\Features\City\States\Contracts\StateRepositoryInterface;
use App\Features\City\States\Repositories\StateRepository;
use Illuminate\Support\ServiceProvider;

class StateBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        StateRepositoryInterface::class => StateRepository::class,
        StateBusinessInterface::class => StateBusiness::class,
    ];
}
