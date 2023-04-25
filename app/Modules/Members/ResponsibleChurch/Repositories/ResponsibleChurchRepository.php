<?php

namespace App\Modules\Members\ResponsibleChurch\Repositories;

use App\Modules\Members\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
use App\Modules\Members\ResponsibleChurch\Models\ResponsibleChurch;

class ResponsibleChurchRepository implements ResponsibleChurchRepositoryInterface
{
    public function findByAdminUserAndChurch(string $adminUserId, string $churchId)
    {
        return ResponsibleChurch::where([
            ResponsibleChurch::ADMIN_USER_ID => $adminUserId,
            ResponsibleChurch::CHURCH_ID => $churchId,
        ])->first();
    }

    public function remove(string $adminUserId, string $churchId)
    {
        ResponsibleChurch::where([
            ResponsibleChurch::ADMIN_USER_ID => $adminUserId,
            ResponsibleChurch::CHURCH_ID => $churchId,
        ])->delete();
    }
}
