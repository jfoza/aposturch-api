<?php

namespace App\Features\Users\AdminUsers\Responses;

class CountAdminUsersResponse
{
    public int|null $adminMasterCount;
    public int|null $adminChurchCount;
    public int|null $adminModuleCount;
    public int|null $assistantCount;
    public int|null $memberCount;
}
