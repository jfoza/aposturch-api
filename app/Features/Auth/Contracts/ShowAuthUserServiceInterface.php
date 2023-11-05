<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Responses\AuthUserResponse;

interface ShowAuthUserServiceInterface
{
    public function execute(AuthDTO $authDTO): AuthUserResponse;
}
