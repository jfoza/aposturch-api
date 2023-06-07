<?php

namespace App\Features\Base\Providers;

use App\Features\Base\Traits\PolicyGenerationTrait;
use App\Shared\Utils\Auth;
use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{
    use PolicyGenerationTrait;

    public function bind(
        mixed $abstractServiceClass,
        mixed $concreteServiceClass,
    ): void
    {
        $this->app->bind(
            $abstractServiceClass,
            function() use ($concreteServiceClass) {
                $service = $this->app->make($concreteServiceClass);

                $service->setPolicy($this->generatePolicyUser());
                $service->setAuthenticatedUser(Auth::authenticate());

                return $service;
            }
        );
    }
}
