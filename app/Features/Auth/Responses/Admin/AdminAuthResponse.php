<?php

namespace App\Features\Auth\Responses\Admin;

class AdminAuthResponse
{
    public string $accessToken;
    public string $tokenType;
    public string $expiresIn;

    public function __construct(
        public AdminUserResponse $user
    ) {}
}
