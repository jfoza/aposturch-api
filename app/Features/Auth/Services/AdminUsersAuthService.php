<?php

namespace App\Features\Auth\Services;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\AdminUsersAuthServiceInterface;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Resources\AdminAuthResource;
use App\Features\Auth\Responses\Admin\AdminAuthResponse;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\Base\Services\Service;
use App\Features\Users\AdminUsers\Contracts\AdminUsersRepositoryInterface;
use App\Features\Users\Rules\Contracts\RulesRepositoryInterface;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Features\Users\Users\Traits\UserAbilityTrait;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Auth;

class AdminUsersAuthService extends Service implements AdminUsersAuthServiceInterface
{
    use UserAbilityTrait;

    public function __construct(
        private readonly AdminUsersRepositoryInterface $adminUsersRepository,
        private readonly RulesRepositoryInterface      $rulesRepository,
        private readonly SessionsRepositoryInterface   $sessionsRepository,
        private readonly AdminAuthResource             $authResource,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(SessionsDTO $sessionsDTO): AdminAuthResponse
    {
        $adminUser = $this->adminUsersRepository->findByEmail($sessionsDTO->email);
        $user      = AuthValidations::userExistsLogin($adminUser);

        AuthValidations::passwordVerify($sessionsDTO->password, $user->password);
        AuthValidations::isActive($user->active);
        AuthValidations::userHasChurch($user);

        $ability = $this->findAllUserAbility($user, $this->rulesRepository);

        $accessToken = Auth::generateAccessToken($user->id);
        $expiresIn   = Auth::getExpiresIn();
        $tokenType   = Auth::getTokenType();
        $currentDate = Helpers::getCurrentTimestampCarbon();

        $this->authResource->setAuthResponse(
            $accessToken,
            $expiresIn,
            $tokenType,
            $user,
            $ability
        );

        $sessionsDTO->userId      = $user->id;
        $sessionsDTO->initialDate = $currentDate;
        $sessionsDTO->finalDate   = $currentDate->addDays(2);
        $sessionsDTO->token       = $accessToken;

        $this->sessionsRepository->create($sessionsDTO);

        return $this->authResource->getAuthResponse();
    }
}
