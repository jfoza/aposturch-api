<?php

namespace App\Modules\Membership\Members\Providers;

use App\Features\Base\Providers\AbstractServiceProvider;
use App\Modules\Membership\Members\Contracts\FindAllMembersResponsibleServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\FindAllMembersResponsibleService;

class MembersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        MembersRepositoryInterface::class => MembersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            FindAllMembersResponsibleServiceInterface::class,
            FindAllMembersResponsibleService::class
        );
    }
}
