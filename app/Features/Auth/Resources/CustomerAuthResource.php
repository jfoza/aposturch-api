<?php

namespace App\Features\Auth\Resources;

use App\Features\Auth\Responses\Customer\CustomerAuthResponse;

class CustomerAuthResource
{
    public function __construct(
        private readonly CustomerAuthResponse $authResponse
    ) {}

    /**
     * @return CustomerAuthResponse
     */
    public function getAuthResponse(): CustomerAuthResponse
    {
        return $this->authResponse;
    }

    /**
     * @param mixed $accessToken
     * @param string $expiresIn
     * @param string $tokenType
     * @param object $user
     */
    public function setAuthResponse(
        mixed $accessToken,
        string $expiresIn,
        string $tokenType,
        object $user,
    ): void
    {
        $this->authResponse->accessToken = $accessToken;
        $this->authResponse->expiresIn   = $expiresIn;
        $this->authResponse->tokenType   = $tokenType;

        $this->authResponse->user->id       = $user->id;
        $this->authResponse->user->email    = $user->email;
        $this->authResponse->user->avatar   = $user->avatar;
        $this->authResponse->user->fullName = $user->name;
        $this->authResponse->user->role     = $user->profile;
        $this->authResponse->user->status   = $user->active;

        $this->authResponse->user->phone         = $user->person->phone;
        $this->authResponse->user->zipCode       = $user->person->zip_code;
        $this->authResponse->user->address       = $user->person->address;
        $this->authResponse->user->numberAddress = $user->person->number_address;
        $this->authResponse->user->complement    = $user->person->complement;
        $this->authResponse->user->district      = $user->person->district;
        $this->authResponse->user->city          = $user->person->city;
    }
}
