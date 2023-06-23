<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface ModulesDataUpdateServiceInterface
{
    public function execute(string $userId, array $modulesId): UpdateMemberResponse;
}
