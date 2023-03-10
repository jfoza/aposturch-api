<?php

namespace App\Features\Base\Providers;

use App\Features\Base\Traits\PolicyTrait;
use Illuminate\Support\ServiceProvider;

abstract class ServiceProviderAbstract extends ServiceProvider
{
    use PolicyTrait;

    public abstract function getBusinessAbstract(): string;
    public abstract function getBusinessConcrete(): string;

    public function register() {
        $this->app->bind($this->getBusinessAbstract(), function() {
            $business = $this->app->make($this->getBusinessConcrete());
            $business->setPolicy($this->generatePolicy());

            return $business;
        });
    }
}
