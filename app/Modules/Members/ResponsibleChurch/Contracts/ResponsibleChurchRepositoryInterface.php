<?php

namespace App\Modules\Members\ResponsibleChurch\Contracts;

interface ResponsibleChurchRepositoryInterface
{
    public function findByAdminUserAndChurch(string $adminUserId, string $churchId);

    public function remove(string $adminUserId, string $churchId);
}
