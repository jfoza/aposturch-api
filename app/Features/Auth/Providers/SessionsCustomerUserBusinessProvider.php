<?php

namespace App\Features\Auth\Providers;

use App\Features\Auth\Business\SessionsCustomerUserBusiness;
use App\Features\Auth\Contracts\SessionsCustomerUserBusinessInterface;
use Illuminate\Support\ServiceProvider;

class SessionsCustomerUserBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        SessionsCustomerUserBusinessInterface::class => SessionsCustomerUserBusiness::class,
    ];
}
