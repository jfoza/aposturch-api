<?php

namespace App\Features\Auth\DTO;

class SessionsDTO
{
    public string|null $email;
    public string|null $password;
    public string|null $ipAddress;
    public string|null $userId;
    public string|null $initialDate;
    public string|null $finalDate;
    public string|null $token;
}
