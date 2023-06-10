<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Responses\LoggedUserResponse;

interface ShowAuthenticatedUserServiceInterface
{
    public function execute(): LoggedUserResponse;
}
