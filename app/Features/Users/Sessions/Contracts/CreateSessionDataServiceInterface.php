<?php

namespace App\Features\Users\Sessions\Contracts;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Users\Sessions\Models\Session;

interface CreateSessionDataServiceInterface
{
    public function execute(AuthDTO $authDTO): Session;
}
