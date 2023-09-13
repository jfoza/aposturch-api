<?php

namespace App\Modules\Membership\Members\Contracts;

interface UpdateStatusMemberServiceInterface
{
    public function execute(string $userId): array;
}
