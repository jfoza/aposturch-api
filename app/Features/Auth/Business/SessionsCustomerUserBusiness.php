<?php

namespace App\Features\Auth\Business;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\SessionsCustomerUserBusinessInterface;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Http\Resources\CustomerAuthResource;
use App\Features\Auth\Http\Responses\Customer\CustomerAuthResponse;
use App\Features\Auth\Validations\AuthValidations;
use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\Sessions\Contracts\SessionsRepositoryInterface;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Auth;

readonly class SessionsCustomerUserBusiness implements SessionsCustomerUserBusinessInterface
{
    public function __construct(
        private CustomerUsersRepositoryInterface $customerUsersRepository,
        private SessionsRepositoryInterface      $sessionsRepository,
        private CustomerAuthResource             $authResource,
    ) {}

    /**
     * @throws AppException
     */
    public function login(SessionsDTO $sessionsDTO): CustomerAuthResponse
    {
        $customerUser = $this->customerUsersRepository->findByUserEmail($sessionsDTO->email);
        $user         = AuthValidations::userExistsLogin($customerUser);

        AuthValidations::validateIfUserHasAlreadyVerifiedEmail($customerUser->verified_email);
        AuthValidations::passwordVerify($sessionsDTO->password, $user->password);
        AuthValidations::isActive($user->active);

        $accessToken = Auth::generateAccessToken($user->id);
        $expiresIn   = Auth::getExpiresIn();
        $tokenType   = Auth::getTokenType();
        $currentDate = Helpers::getCurrentTimestampCarbon();

        $this->authResource->setAuthResponse(
            $accessToken,
            $expiresIn,
            $tokenType,
            $user,
        );

        $sessionsDTO->userId      = $user->id;
        $sessionsDTO->initialDate = $currentDate;
        $sessionsDTO->finalDate   = $currentDate->addDays(2);
        $sessionsDTO->token       = $accessToken;

        $this->sessionsRepository->create($sessionsDTO);

        return $this->authResource->getAuthResponse();
    }
}
