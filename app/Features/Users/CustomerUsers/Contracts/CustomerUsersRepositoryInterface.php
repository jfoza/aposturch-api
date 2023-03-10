<?php

namespace App\Features\Users\CustomerUsers\Contracts;

use App\Features\Users\CustomerUsers\DTO\CustomerUsersDTO;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;

interface CustomerUsersRepositoryInterface
{
    public function findAll(CustomerUsersFiltersDTO $customerUsersFiltersDTO);
    public function findById(string $customerUserId);
    public function findByEmail(string $email);
    public function findByUserEmail(string $email);
    public function findByUserId(string $userId);
    public function create(CustomerUsersDTO $customerUsersDTO);
    public function authorizeCustomerUser(string $customerUserId);
}
