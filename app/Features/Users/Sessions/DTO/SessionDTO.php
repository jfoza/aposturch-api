<?php

namespace App\Features\Users\Sessions\DTO;

use Carbon\Carbon;

class SessionDTO
{
    public string $userId;
    public Carbon $initialDate;
    public Carbon $finalDate;
    public string $token;
    public ?string $ipAddress;
}
