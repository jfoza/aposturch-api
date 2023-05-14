<?php

namespace App\Modules\Membership\Members\Contracts;

interface ShowByUserIdServiceInterface
{
    public function execute(string $userId);
}
