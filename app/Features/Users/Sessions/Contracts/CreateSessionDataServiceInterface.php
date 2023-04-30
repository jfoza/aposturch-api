<?php

namespace App\Features\Users\Sessions\Contracts;

use App\Features\Users\Sessions\DTO\SessionDTO;
use App\Features\Users\Sessions\Models\Session;

interface CreateSessionDataServiceInterface
{
    public function execute(SessionDTO $sessionDTO): Session;
}
