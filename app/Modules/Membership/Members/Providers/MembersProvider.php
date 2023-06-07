<?php

namespace App\Modules\Membership\Members\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Contracts\UpdateMemberServiceInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\CreateMemberService;
use App\Modules\Membership\Members\Services\FindAllMembersService;
use App\Modules\Membership\Members\Services\ShowByUserIdService;
use App\Modules\Membership\Members\Services\UpdateMemberService;

class MembersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        MembersRepositoryInterface::class => MembersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllMembersServiceInterface::class,
            FindAllMembersService::class
        );

        $this->bind(
            ShowByUserIdServiceInterface::class,
            ShowByUserIdService::class
        );

        $this->bind(
            CreateMemberServiceInterface::class,
            CreateMemberService::class
        );

        $this->bind(
            UpdateMemberServiceInterface::class,
            UpdateMemberService::class
        );
    }
}
