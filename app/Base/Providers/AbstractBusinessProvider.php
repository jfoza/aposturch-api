<?php

namespace App\Base\Providers;

use App\Base\Traits\PolicyGenerationTrait;
use Illuminate\Support\ServiceProvider;

abstract class AbstractBusinessProvider extends ServiceProvider
{
    use PolicyGenerationTrait;

    public abstract function getBusinessAbstract(): string;
    public abstract function getBusinessConcrete(): string;

    public function register() {
        $this->app->bind($this->getBusinessAbstract(), function() {
            $business = $this->app->make($this->getBusinessConcrete());
            $business->setPolicy($this->generatePolicyUser());

            return $business;
        });
    }
}
