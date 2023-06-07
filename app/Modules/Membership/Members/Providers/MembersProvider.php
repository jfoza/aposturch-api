<?php

namespace App\Modules\Membership\Members\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Contracts\UpdateMemberServiceInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\CreateMemberAuthenticatedService;
use App\Modules\Membership\Members\Services\FindAllMembersAuthenticatedService;
use App\Modules\Membership\Members\Services\ShowByUserIdAuthenticatedService;
use App\Modules\Membership\Members\Services\UpdateMemberAuthenticatedService;

class MembersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        MembersRepositoryInterface::class => MembersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllMembersServiceInterface::class,
            FindAllMembersAuthenticatedService::class
        );

        $this->bind(
            ShowByUserIdServiceInterface::class,
            ShowByUserIdAuthenticatedService::class
        );

        $this->bind(
            CreateMemberServiceInterface::class,
            CreateMemberAuthenticatedService::class
        );

        $this->bind(
            UpdateMemberServiceInterface::class,
            UpdateMemberAuthenticatedService::class
        );
    }
}
