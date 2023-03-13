<?php

namespace App\Features\Users\Profiles\Providers;

use App\Features\Base\Providers\ServiceProviderAbstract;
use App\Features\Users\Profiles\Business\ProfilesBusiness;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesBusinessInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Infra\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesByUserAbilityService;

class ProfilesBusinessProvider extends ServiceProviderAbstract
{
    public array $bindings = [
        FindAllProfilesByUserAbilityServiceInterface::class => FindAllProfilesByUserAbilityService::class,
        ProfilesRepositoryInterface::class => ProfilesRepository::class,
    ];

    public function getBusinessAbstract(): string
    {
        return ProfilesBusinessInterface::class;
    }

    public function getBusinessConcrete(): string
    {
        return ProfilesBusiness::class;
    }
}
