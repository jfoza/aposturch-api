<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Http\Responses\CountAdminUsersResponse;
use App\Shared\ACL\Policy;

interface ShowCountAdminUsersByProfileInterface
{
    public function execute(Policy $policy): CountAdminUsersResponse;
}
