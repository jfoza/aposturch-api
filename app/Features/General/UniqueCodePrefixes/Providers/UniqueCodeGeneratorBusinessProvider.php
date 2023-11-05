<?php

namespace App\Features\General\UniqueCodePrefixes\Providers;

use App\Features\General\UniqueCodePrefixes\Business\UniqueCodeGeneratorBusiness;
use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodeGeneratorBusinessInterface;
use Illuminate\Support\ServiceProvider;

class UniqueCodeGeneratorBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        UniqueCodeGeneratorBusinessInterface::class => UniqueCodeGeneratorBusiness::class,
    ];
}
