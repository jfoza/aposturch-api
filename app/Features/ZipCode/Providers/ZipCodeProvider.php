<?php

namespace App\Features\ZipCode\Providers;

use App\Features\ZipCode\Business\ZipCodeBusiness;
use App\Features\ZipCode\Contracts\ZipCodeBusinessInterface;
use Illuminate\Support\ServiceProvider;

class ZipCodeProvider extends ServiceProvider
{
    public array $bindings = [
        ZipCodeBusinessInterface::class => ZipCodeBusiness::class,
    ];
}
