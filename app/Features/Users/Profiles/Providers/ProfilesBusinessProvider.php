<?php

namespace App\Features\Users\Profiles\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\FindAllProfilesInListMembersServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesRepositoryInterface;
use App\Features\Users\Profiles\Repositories\ProfilesRepository;
use App\Features\Users\Profiles\Services\FindAllProfilesByUserAbilityAuthenticatedService;
use App\Features\Users\Profiles\Services\FindAllProfilesInListMembersAuthenticatedService;

class ProfilesBusinessProvider extends AbstractServiceProvider
{
    public array $bindings = [
        ProfilesRepositoryInterface::class => ProfilesRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllProfilesByUserAbilityServiceInterface::class,
            FindAllProfilesByUserAbilityAuthenticatedService::class
        );

        $this->bind(
            FindAllProfilesInListMembersServiceInterface::class,
            FindAllProfilesInListMembersAuthenticatedService::class
        );
    }
}
