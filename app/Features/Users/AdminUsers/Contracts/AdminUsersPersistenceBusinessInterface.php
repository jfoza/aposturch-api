<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\Users\DTO\UserDTO;

interface AdminUsersPersistenceBusinessInterface
{
    public function create(UserDTO $userDTO);
    public function save(UserDTO $userDTO);
}
