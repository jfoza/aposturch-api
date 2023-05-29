<?php

namespace App\Features\City\Cities\Providers;

use App\Features\City\Cities\Business\CityBusiness;
use App\Features\City\Cities\Contracts\CityBusinessInterface;
use App\Features\City\Cities\Contracts\CityRepositoryInterface;
use App\Features\City\Cities\Repositories\CityRepository;
use Illuminate\Support\ServiceProvider;

class CityBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        CityRepositoryInterface::class => CityRepository::class,
        CityBusinessInterface::class => CityBusiness::class,
    ];
}
