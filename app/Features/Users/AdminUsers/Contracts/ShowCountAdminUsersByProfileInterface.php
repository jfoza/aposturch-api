<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Responses\CountAdminUsersResponse;

interface ShowCountAdminUsersByProfileInterface
{
    public function execute(): CountAdminUsersResponse;
}
