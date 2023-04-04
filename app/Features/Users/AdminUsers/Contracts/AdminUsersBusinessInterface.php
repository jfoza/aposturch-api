<?php

namespace App\Features\Users\AdminUsers\Contracts;

use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\Users\DTO\UserDTO;

interface AdminUsersBusinessInterface
{
    public function findAll(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findByUserId(AdminUsersFiltersDTO $adminUsersFiltersDTO);
    public function findCountByProfiles();
    public function findLoggedUser();
    public function create(UserDTO $userDTO);
    public function save(UserDTO $userDTO);
}
