<?php

namespace App\Features\Auth\Services;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Features\Auth\Traits\ProfilesVerificationTrait;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Traits\UserAbilityTrait;
use App\Shared\Utils\Hash;

class ShowAuthUserService implements ShowAuthUserServiceInterface
{
    use UserAbilityTrait;
    use ProfilesVerificationTrait;

    private object $user;

    public function __construct(
        private readonly UsersRepositoryInterface $usersRepository,
        private readonly RulesRepositoryInterface $rulesRepository,
        private readonly AuthUserResponse $authUserResponse
    )
    {}

    /**
     * @throws AppException
     */
    public function execute(AuthDTO $authDTO): AuthUserResponse
    {
        if(!$this->user = $this->usersRepository->findByEmail($authDTO->email))
        {
            AuthValidations::dispatchLoginException();
        }

        if(!Hash::compareHash($authDTO->password, $this->user->password))
        {
            AuthValidations::dispatchLoginException();
        }

        if(!$this->user->active)
        {
            AuthValidations::dispatchInactiveUserException();
        }

        $profiles = collect($this->user->profile);

        match (true)
        {
            $this->supportAuth($profiles)        => $this->validateByAdminMaster(),
            $this->administrativeAuth($profiles) => $this->validateByAdminMaster(),
            $this->boardAuth($profiles)          => $this->validateByBoard(),
        };

        return $this->getAuthUserResponse();
    }

    /**
     * @throws AppException
     */
    private function validateByAdminMaster()
    {
        if(!$this->user->adminUser)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function validateByBoard()
    {
        if(!$member = $this->user->member)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    private function getAuthUserResponse(): AuthUserResponse
    {
        $ability = $this->findAllUserAbility($this->user, $this->rulesRepository);

        $this->authUserResponse->id        = $this->user->id;
        $this->authUserResponse->email     = $this->user->email;
        $this->authUserResponse->avatar    = $this->user->avatar;
        $this->authUserResponse->fullName  = $this->user->name;
        $this->authUserResponse->role      = $this->user->profile;
        $this->authUserResponse->status    = $this->user->active;
        $this->authUserResponse->ability   = $ability;

        return $this->authUserResponse;
    }
}
