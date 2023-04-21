<?php

namespace App\Features\Auth\Contracts;

use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Responses\Customer\CustomerAuthResponse;

interface SessionsCustomerUserBusinessInterface
{
    public function login(SessionsDTO $sessionsDTO): CustomerAuthResponse;
}
