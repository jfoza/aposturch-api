<?php

namespace App\Features\Auth\Services;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\Users\Profiles\Validations\ProfileHierarchyValidations;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Features\Users\Users\Traits\UserAbilityTrait;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Utils\Hash;

class ShowAuthUserService implements ShowAuthUserServiceInterface
{
    use UserAbilityTrait;

    private ?object $user;
    private AuthDTO $authDTO;

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
        $this->setAuthDTO($authDTO);
        $this->setUser($this->getAuthDTO()->email);

        $this->passwordValidation();
        $this->userStatusValidation();

        $profiles = collect($this->getUser()->profile);

        match (true)
        {
            ProfileHierarchyValidations::supportAuth($profiles)        => $this->validateByAdminMaster(),
            ProfileHierarchyValidations::administrativeAuth($profiles) => $this->validateByAdminMaster(),
            ProfileHierarchyValidations::boardAuth($profiles)          => $this->validateByGeneralAdminProfiles(),
        };

        return $this->getAuthUserResponse();
    }

    private function getAuthDTO(): AuthDTO
    {
        return $this->authDTO;
    }

    private function setAuthDTO(AuthDTO $authDTO): void
    {
        $this->authDTO = $authDTO;
    }

    private function getUser(): ?object
    {
        return $this->user;
    }

    /**
     * @throws AppException
     */
    private function setUser(string $email): void
    {
        if(!$this->user = $this->usersRepository->findByEmail($email))
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function passwordValidation(): void
    {
        if($this->getAuthDTO()->authType == AuthTypesEnum::EMAIL_PASSWORD->value)
        {
            if(!Hash::compareHash($this->getAuthDTO()->password, $this->getUser()->password))
            {
                AuthValidations::dispatchLoginException();
            }
        }
    }

    /**
     * @throws AppException
     */
    private function userStatusValidation(): void
    {
        if(!$this->getUser()->active)
        {
            AuthValidations::dispatchInactiveUserException();
        }
    }

    /**
     * @throws AppException
     */
    private function validateByAdminMaster(): void
    {
        if(!$this->getUser()->adminUser)
        {
            AuthValidations::dispatchLoginException();
        }
    }

    /**
     * @throws AppException
     */
    private function validateByGeneralAdminProfiles(): void
    {
        if(!$member = $this->getUser()->member)
        {
            AuthValidations::dispatchLoginException();
        }

        if(empty($member->church))
        {
            AuthValidations::memberUserNoHasChurch();
        }

        $this->authUserResponse->churches = $member->church;
    }

    private function getAuthUserResponse(): AuthUserResponse
    {
        $ability = $this->findAllUserAbility($this->getUser(), $this->rulesRepository);

        $this->authUserResponse->id       = $this->getUser()->id;
        $this->authUserResponse->email    = $this->getUser()->email;
        $this->authUserResponse->avatarId = $this->getUser()->avatar_id;
        $this->authUserResponse->fullName = $this->getUser()->name;
        $this->authUserResponse->role     = $this->getUser()->profile;
        $this->authUserResponse->status   = $this->getUser()->active;
        $this->authUserResponse->ability  = $ability;

        return $this->authUserResponse;
    }
}
