<?php

namespace App\Features\Users\Profiles\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesByUserAbilityService;

class ProfilesBusinessProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ProfilesRepositoryInterface::class => ProfilesRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllProfilesByUserAbilityServiceInterface::class,
            FindAllProfilesByUserAbilityService::class
        );
    }
}
