<?php

namespace App\Modules\Membership\Members\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\MembersRepositoryInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\AddressDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ChurchDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\GeneralDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ModulesDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\PasswordDataUpdateServiceInterface;
use App\Modules\Membership\Members\Contracts\Updates\ProfileDataUpdateServiceInterface;
use App\Modules\Membership\Members\Repositories\MembersRepository;
use App\Modules\Membership\Members\Services\CreateMemberService;
use App\Modules\Membership\Members\Services\FindAllMembersService;
use App\Modules\Membership\Members\Services\ShowByUserIdService;
use App\Modules\Membership\Members\Services\Updates\AddressDataUpdateService;
use App\Modules\Membership\Members\Services\Updates\ChurchDataUpdateService;
use App\Modules\Membership\Members\Services\Updates\GeneralDataUpdateService;
use App\Modules\Membership\Members\Services\Updates\ModulesDataUpdateService;
use App\Modules\Membership\Members\Services\Updates\PasswordDataUpdateService;
use App\Modules\Membership\Members\Services\Updates\ProfileDataUpdateService;

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
            GeneralDataUpdateServiceInterface::class,
            GeneralDataUpdateService::class
        );
        $this->bind(
            AddressDataUpdateServiceInterface::class,
            AddressDataUpdateService::class
        );
        $this->bind(
            ChurchDataUpdateServiceInterface::class,
            ChurchDataUpdateService::class
        );
        $this->bind(
            ModulesDataUpdateServiceInterface::class,
            ModulesDataUpdateService::class
        );
        $this->bind(
            ProfileDataUpdateServiceInterface::class,
            ProfileDataUpdateService::class
        );
        $this->bind(
            ProfileDataUpdateServiceInterface::class,
            ProfileDataUpdateService::class
        );
        $this->bind(
            PasswordDataUpdateServiceInterface::class,
            PasswordDataUpdateService::class
        );
    }
}
