<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Http\Responses\Admin\AdminAuthResponse;

interface SessionsAdminUserBusinessInterface
{
    public function login(SessionsDTO $sessionsDTO): AdminAuthResponse;
}
