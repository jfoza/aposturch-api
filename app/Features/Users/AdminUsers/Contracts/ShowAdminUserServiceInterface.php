<?php

namespace App\Features\Users\AdminUsers\Contracts;

interface ShowAdminUserServiceInterface
{
    public function execute(string $userId): object;
}
