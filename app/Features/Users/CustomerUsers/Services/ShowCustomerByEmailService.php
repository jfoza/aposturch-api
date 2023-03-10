<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;

class ShowCustomerByEmailService
{
    public function __construct(
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository
    ) {}

    public function execute(string $email)
    {
        return $this->customerUsersRepository->findByEmail($email);
    }
}
