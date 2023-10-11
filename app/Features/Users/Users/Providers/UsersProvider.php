<?php

namespace App\Features\Users\Users\Providers;

use App\Base\Providers\AbstractServiceProvider;
use App\Features\Users\AdminUsers\Contracts\ShowAuthenticatedUserServiceInterface;
use App\Features\Users\Users\Contracts\UserEmailAlreadyExistsServiceInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Contracts\UserUploadImageServiceInterface;
use App\Features\Users\Users\Repositories\UsersRepository;
use App\Features\Users\Users\Services\ShowAuthenticatedUserService;
use App\Features\Users\Users\Services\UserEmailAlreadyExistsService;
use App\Features\Users\Users\Services\UserUploadImageService;
use App\Modules\Membership\Members\Contracts\UpdateStatusMemberServiceInterface;
use App\Modules\Membership\Members\Services\UpdateStatusMemberService;

class UsersProvider extends AbstractServiceProvider
{
    public array $bindings = [
        UsersRepositoryInterface::class => UsersRepository::class,
    ];

    public function register()
    {
        $this->bind(
            ShowAuthenticatedUserServiceInterface::class,
            ShowAuthenticatedUserService::class,
        );

        $this->bind(
            UpdateStatusMemberServiceInterface::class,
            UpdateStatusMemberService::class,
        );

        $this->bind(
            UserEmailAlreadyExistsServiceInterface::class,
            UserEmailAlreadyExistsService::class,
        );

        $this->bind(
            UserUploadImageServiceInterface::class,
            UserUploadImageService::class,
        );
    }
}
