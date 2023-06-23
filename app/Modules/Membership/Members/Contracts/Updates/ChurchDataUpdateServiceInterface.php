<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface ChurchDataUpdateServiceInterface
{
    public function execute(string $userId, string $churchId): UpdateMemberResponse;
}
