<?php

namespace App\Features\Users\CustomerUsers\Services;

use App\Features\Users\CustomerUsers\Contracts\CustomerUsersRepositoryInterface;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;

class ListCustomersService
{
    public function __construct(
        private readonly CustomerUsersRepositoryInterface $customerUsersRepository,
    ) {}

    public function execute(
        CustomerUsersFiltersDTO $customerUsersFiltersDTO,
    )
    {
        return $this->customerUsersRepository->findAll($customerUsersFiltersDTO);
    }
}
