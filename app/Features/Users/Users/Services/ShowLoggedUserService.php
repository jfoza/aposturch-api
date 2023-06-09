<?php

namespace App\Features\Users\Users\Services;

use App\Features\Base\Services\AuthenticatedService;
use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Responses\LoggedUserResponse;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Users\Traits\UserAbilityTrait;
use App\Shared\Utils\Auth;

class ShowLoggedUserService extends AuthenticatedService implements ShowLoggedUserServiceInterface
{
    use UserAbilityTrait;

    public function __construct(
        private readonly RulesRepositoryInterface $rulesRepository,
        private readonly LoggedUserResponse $loggedUserResponse,
    ) {}

    public function execute(): LoggedUserResponse
    {
        $user = $this->getAuthenticatedUser();

        $ability = $this->findAllUserAbility($user, $this->rulesRepository);

        $this->loggedUserResponse->id       = $user->id;
        $this->loggedUserResponse->email    = $user->email;
        $this->loggedUserResponse->avatarId = $user->avatar_id;
        $this->loggedUserResponse->fullName = $user->name;
        $this->loggedUserResponse->role     = $user->profile->first();
        $this->loggedUserResponse->status   = $user->active;
        $this->loggedUserResponse->ability  = $ability;

        return $this->loggedUserResponse;
    }
}
