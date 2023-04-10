<?php

namespace App\Features\Base\Providers;

use App\Features\Base\Traits\PolicyGenerationTrait;
use Illuminate\Support\ServiceProvider;

abstract class AbstractServiceProvider extends ServiceProvider
{
    use PolicyGenerationTrait;

    public function bind(
        mixed $abstractServiceClass,
        mixed $concreteServiceClass,
    ) {
        $this->app->bind($abstractServiceClass, function() use ($concreteServiceClass) {
            $business = $this->app->make($concreteServiceClass);
            $business->setPolicy($this->generatePolicyUser());

            return $business;
        });
    }
}
