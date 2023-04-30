<?php

namespace App\Features\Auth\Services;

use App\Features\Auth\Contracts\AuthGenerateServiceInterface;
use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Responses\AuthUserResponse;
use App\Shared\Utils\Auth;

readonly class AuthGenerateService implements AuthGenerateServiceInterface
{
    public function __construct(
        private AuthResponse $authResponse,
    ) {}

    public function execute(AuthUserResponse $authUserResponse): AuthResponse
    {
        $accessToken = Auth::generateAccessToken($authUserResponse->id);

        $this->authResponse->accessToken = $accessToken;
        $this->authResponse->expiresIn   = Auth::getExpiresIn();
        $this->authResponse->tokenType   = Auth::getTokenType();

        $this->authResponse->user = $authUserResponse;

        return $this->authResponse;
    }
}
