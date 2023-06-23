<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface PasswordDataUpdateServiceInterface
{
    public function execute(string $userId, string $password): UpdateMemberResponse;
}
