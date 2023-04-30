<?php

namespace App\Modules\Membership\ResponsibleChurch\Repositories;

use App\Modules\Membership\ResponsibleChurch\Contracts\ResponsibleChurchRepositoryInterface;
use App\Modules\Membership\ResponsibleChurch\Models\ResponsibleChurch;

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
