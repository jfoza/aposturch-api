<?php

namespace App\Features\Users\AdminUsers\Services;

use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Http\Responses\LoggedUserResponse;
use App\Features\Users\Rules\Infra\Repositories\RulesRepository;
use App\Features\Users\Users\Traits\UserAbilityTrait;
use App\Shared\Utils\Auth;

readonly class ShowLoggedUserService implements ShowLoggedUserServiceInterface
{
    use UserAbilityTrait;

    public function __construct(
        private RulesRepository    $rulesRepository,
        private LoggedUserResponse $loggedUserResponse,
    ) {}

    public function execute(): LoggedUserResponse
    {
        $user = Auth::getUser();

        $ability = $this->findAllUserAbility($user, $this->rulesRepository);

        $this->loggedUserResponse->id       = $user['id'];
        $this->loggedUserResponse->email    = $user['email'];
        $this->loggedUserResponse->avatar   = $user['avatar'];
        $this->loggedUserResponse->fullName = $user['name'];
        $this->loggedUserResponse->role     = $user['profile']->first();
        $this->loggedUserResponse->status   = $user['active'];
        $this->loggedUserResponse->ability  = $ability;

        return $this->loggedUserResponse;
    }
}
