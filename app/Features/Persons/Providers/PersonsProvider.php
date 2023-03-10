<?php

namespace App\Features\Persons\Providers;

use App\Features\Persons\Contracts\PersonsRepositoryInterface;
use App\Features\Persons\Infra\Repositories\PersonsRepository;
use Illuminate\Support\ServiceProvider;

class PersonsProvider extends ServiceProvider
{
    public array $bindings = [
        PersonsRepositoryInterface::class => PersonsRepository::class,
    ];
}
