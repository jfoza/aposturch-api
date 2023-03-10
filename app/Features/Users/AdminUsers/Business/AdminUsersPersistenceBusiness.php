<?php

namespace App\Features\Users\AdminUsers\Business;

use App\Features\Base\Business\Business;
use App\Features\Users\AdminUsers\Contracts\AdminUsersInsertFactoryInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersPersistenceBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersUpdateFactoryInterface;
use App\Features\Users\Users\DTO\UserDTO;

class AdminUsersPersistenceBusiness
    extends Business
    implements AdminUsersPersistenceBusinessInterface
{
    public function __construct(
        private readonly AdminUsersInsertFactoryInterface $adminUsersInsertFactory,
        private readonly AdminUsersUpdateFactoryInterface $adminUsersUpdateFactory,
    ) {}

    public function create(UserDTO $userDTO)
    {
        return $this->adminUsersInsertFactory->execute($userDTO);
    }

    public function save(UserDTO $userDTO)
    {
        return $this->adminUsersUpdateFactory->execute($userDTO);
    }
}
