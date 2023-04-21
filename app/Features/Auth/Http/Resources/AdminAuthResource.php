<?php

namespace App\Features\Auth\Http\Resources;

use App\Features\Auth\Http\Responses\Admin\AdminAuthResponse;

readonly class AdminAuthResource
{
    public function __construct(
        private AdminAuthResponse $authResponse
    ) {}

    /**
     * @return AdminAuthResponse
     */
    public function getAuthResponse(): AdminAuthResponse
    {
        return $this->authResponse;
    }

    /**
     * @param mixed $accessToken
     * @param string $expiresIn
     * @param string $tokenType
     * @param object $user
     * @param array $ability
     */
    public function setAuthResponse(
        mixed $accessToken,
        string $expiresIn,
        string $tokenType,
        object $user,
        array $ability,
    ): void
    {
        $this->authResponse->accessToken = $accessToken;
        $this->authResponse->expiresIn = $expiresIn;
        $this->authResponse->tokenType = $tokenType;

        $this->authResponse->user->id       = $user->id;
        $this->authResponse->user->email    = $user->email;
        $this->authResponse->user->avatar   = $user->avatar;
        $this->authResponse->user->fullName = $user->name;
        $this->authResponse->user->role     = $user->profile;
        $this->authResponse->user->status   = $user->active;
        $this->authResponse->user->churches = $user->church;

        $this->authResponse->user->ability = $ability;
    }
}
