<?php

namespace App\Modules\Membership\Church\Contracts;

interface RemoveUserChurchRelationshipServiceInterface
{
    public function execute(string $userId);
}
