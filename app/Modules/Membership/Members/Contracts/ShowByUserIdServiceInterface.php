<?php

namespace App\Modules\Membership\Members\Contracts;

use App\Modules\Membership\Members\Responses\MemberResponse;

interface ShowByUserIdServiceInterface
{
    public function execute(string $userId): MemberResponse;
}
