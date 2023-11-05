<?php

namespace App\Features\Auth\Business;

use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Contracts\ShowAuthUserServiceInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthResponse;
use App\Features\Users\Sessions\Contracts\CreateSessionDataServiceInterface;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Helpers\Helpers;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthBusiness implements AuthBusinessInterface
{
    private AuthDTO $authDTO;

    public function __construct(
        private ShowAuthUserServiceInterface $showAuthUserService,
        private AuthGenerateServiceInterface $authGenerateService,
        private CreateSessionDataServiceInterface $createSessionDataService,
    ) {}

    /**
     * @throws AppException
     */
    public function handle(AuthDTO $authDTO): AuthResponse
    {
        $this->authDTO = $authDTO;

        if($this->authDTO->authType == AuthTypesEnum::GOOGLE->value)
        {
            $this->setAuthByGoogle();
        }

        $authUserResponse    = $this->showAuthUserService->execute($this->authDTO);
        $authGenerateService = $this->authGenerateService->execute($authUserResponse);

        $initialDate = Helpers::getCurrentTimestampCarbon();
        $finalDate   = Helpers::getCurrentTimestampCarbon()->addDays(2);

        $this->authDTO->userId      = $authUserResponse->id;
        $this->authDTO->initialDate = $initialDate;
        $this->authDTO->finalDate   = $finalDate;
        $this->authDTO->token       = $authGenerateService->accessToken;

        $this->createSessionDataService->execute($this->authDTO);

        return $authGenerateService;
    }

    /**
     * @return void
     * @throws AppException
     */
    private function setAuthByGoogle(): void
    {
        try
        {
            $response = Socialite::driver('google')->stateless()->userFromToken($this->authDTO->googleAuthToken);

            $this->authDTO->email = $response->email;
        }
        catch (\Exception $e)
        {
            EnvironmentException::dispatchException($e, Response::HTTP_UNAUTHORIZED);
        }
    }
}
