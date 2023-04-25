<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\Responses\AdminUserResponse;
use App\Features\Users\Users\DTO\UserDTO;

interface CreateAdminUserServiceInterface
{
    public function execute(UserDTO $userDTO): AdminUserResponse;
}
