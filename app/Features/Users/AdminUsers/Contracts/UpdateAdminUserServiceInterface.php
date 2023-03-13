<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Http\Responses\AdminUserResponse;
use App\Features\Users\Users\DTO\UserDTO;
use App\Shared\ACL\Policy;

interface UpdateAdminUserServiceInterface
{
    public function execute(UserDTO $userDTO, Policy $policy): AdminUserResponse;
}
