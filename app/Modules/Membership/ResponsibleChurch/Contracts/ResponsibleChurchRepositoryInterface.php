<?php

namespace App\Modules\Membership\ResponsibleChurch\Contracts;

interface ResponsibleChurchRepositoryInterface
{
    public function findByAdminUserAndChurch(string $adminUserId, string $churchId);

    public function remove(string $adminUserId, string $churchId);
}
