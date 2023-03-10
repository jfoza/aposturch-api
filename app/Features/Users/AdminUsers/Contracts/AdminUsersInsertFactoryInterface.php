<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\Users\DTO\UserDTO;

interface AdminUsersInsertFactoryInterface
{
    public function execute(UserDTO $userDTO);
}
