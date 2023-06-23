<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface ProfileDataUpdateServiceInterface
{
    public function execute(string $userId, string $profileId): UpdateMemberResponse;
}
