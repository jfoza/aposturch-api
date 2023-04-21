<?php

namespace App\Features\Auth\Responses\Customer;

class CustomerAuthResponse
{
    public string $accessToken;
    public string $tokenType;
    public string $expiresIn;

    public function __construct(
        public CustomerUserResponse $user
    ) {}
}
