<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Http\Responses\LoggedUserResponse;

interface ShowLoggedUserServiceInterface
{
    public function execute(): LoggedUserResponse;
}
