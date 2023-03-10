<?php

namespace App\Features\Users\CustomerUsers\Providers;

use App\Features\Users\CustomerUsers\Business\PublicCustomerUsersBusiness;
use App\Features\Users\CustomerUsers\Contracts\PublicCustomerUsersBusinessInterface;
use Illuminate\Support\ServiceProvider;

class PublicCustomerUsersBusinessProvider extends ServiceProvider
{
    public array $bindings = [
        PublicCustomerUsersBusinessInterface::class => PublicCustomerUsersBusiness::class,
    ];
}
