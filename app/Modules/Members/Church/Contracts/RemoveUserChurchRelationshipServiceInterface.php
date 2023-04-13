<?php

namespace App\Modules\Members\Church\Contracts;

interface RemoveUserChurchRelationshipServiceInterface
{
    public function execute(string $userId);
}
