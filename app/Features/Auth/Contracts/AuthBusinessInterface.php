<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthResponse;

interface AuthBusinessInterface
{
    public function authenticate(AuthDTO $authDTO): AuthResponse;
}
