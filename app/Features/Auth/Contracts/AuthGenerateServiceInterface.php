<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\Responses\AuthResponse;
use App\Features\Auth\Responses\AuthUserResponse;

interface AuthGenerateServiceInterface
{
    public function execute(AuthUserResponse $authUserResponse): AuthResponse;
}
