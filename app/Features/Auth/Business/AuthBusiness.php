<?php

namespace App\Features\Auth\Business;
;

use App\Exceptions\AppException;
use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Services\ShowAuthUserService;
use App\Features\Users\Sessions\Contracts\CreateSessionDataServiceInterface;
use App\Shared\Helpers\Helpers;

readonly class AuthBusiness implements AuthBusinessInterface
{
    public function __construct(
        private ShowAuthUserService $showAuthUserService,
        private AuthGenerateServiceInterface $authGenerateService,
        private CreateSessionDataServiceInterface $createSessionDataService,
    ) {}

    /**
     * @throws AppException
     */
    public function authenticate(AuthDTO $authDTO): AuthResponse
    {
        $authUserResponse    = $this->showAuthUserService->execute($authDTO);
        $authGenerateService = $this->authGenerateService->execute($authUserResponse);

        $initialDate = Helpers::getCurrentTimestampCarbon();
        $finalDate   = Helpers::getCurrentTimestampCarbon()->addDays(2);

        $authDTO->sessionDTO->userId      = $authUserResponse->id;
        $authDTO->sessionDTO->initialDate = $initialDate;
        $authDTO->sessionDTO->finalDate   = $finalDate;
        $authDTO->sessionDTO->token       = $authGenerateService->accessToken;
        $authDTO->sessionDTO->ipAddress   = $authDTO->ipAddress;

        $this->createSessionDataService->execute($authDTO->sessionDTO);

        return $authGenerateService;
    }
}
