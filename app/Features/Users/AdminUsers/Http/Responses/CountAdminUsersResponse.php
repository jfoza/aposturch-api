<?php

namespace App\Features\Users\AdminUsers\Http\Responses;

class CountAdminUsersResponse
{
    public int|null $adminMasterCount;
    public int|null $adminChurchCount;
    public int|null $adminDepartmentCount;
    public int|null $assistantCount;
    public int|null $memberCount;
}
