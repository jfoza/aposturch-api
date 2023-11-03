<?php

namespace App\Features\Users\Users\Contracts;

interface RemoveUserAvatarServiceInterface
{
    public function execute(string $userId);
}
