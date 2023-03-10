<?php

namespace App\Features\Users\Profiles\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\Profiles\Business\ProfilesListFactory;
use App\Features\Users\Profiles\Contracts\ProfilesListFactoryInterface;

class ProfilesListFactoryProvider extends ServiceProviderAbstract
{
    public function getBusinessAbstract(): string
    {
        return ProfilesListFactoryInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return ProfilesListFactory::class;
    }
}
