<?php

namespace App\Features\Base\Providers;

use App\Features\Base\Traits\PolicyGenerationTrait;
use App\Features\Base\Traits\UserAuthTrait;
use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{
    use PolicyGenerationTrait;
    use UserAuthTrait;

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
                $service->setChurchesUserAuth($this->getChurchesUserAuth());

                return $service;
            }
        );
    }
}
