<?php

namespace App\Features\Users\CustomerUsers\Contracts;

use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;
use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;
use App\Features\Users\Users\DTO\UserDTO;

interface CustomerUsersBusinessInterface
{
    public function findAll(CustomerUsersFiltersDTO $customerUsersFiltersDTO);
    public function findById(string $customerUserId);
    public function create(UserDTO $userDTO): CustomerUserResponse;
    public function save(UserDTO $userDTO): CustomerUserResponse;
}
