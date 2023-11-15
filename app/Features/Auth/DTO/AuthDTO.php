<?php

namespace App\Features\Auth\DTO;

use Carbon\Carbon;

class AuthDTO
{
    public string $authType;
    public string $email;
    public ?string $password;
    public ?string $ipAddress;
    public ?string $googleAuthToken;

    public string $userId;
    public Carbon $initialDate;
    public Carbon $finalDate;
    public string $token;
}
