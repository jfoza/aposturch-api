<?php

namespace App\Features\Auth\DTO;

use App\Features\Users\Sessions\DTO\SessionDTO;

class AuthDTO
{
    public string|null $email;
    public string|null $password;
    public string|null $ipAddress;

    public function __construct(
        public SessionDTO $sessionDTO
    ) {}
}
