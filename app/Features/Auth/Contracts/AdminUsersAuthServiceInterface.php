<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Responses\Admin\AdminAuthResponse;

interface AdminUsersAuthServiceInterface
{
    public function execute(SessionsDTO $sessionsDTO): AdminAuthResponse;
}
