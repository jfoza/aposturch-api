<?php

namespace App\Features\Users\Profiles\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesByUserAbilityAuthenticatedService;

class ProfilesBusinessProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ProfilesRepositoryInterface::class => ProfilesRepository::class,
    ];

    public function register(): void
    {
        $this->bind(
            FindAllProfilesByUserAbilityServiceInterface::class,
            FindAllProfilesByUserAbilityAuthenticatedService::class
        );
    }
}
