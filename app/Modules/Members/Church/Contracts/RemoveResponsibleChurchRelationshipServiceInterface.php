<?php

namespace App\Modules\Members\Church\Contracts;

interface RemoveResponsibleChurchRelationshipServiceInterface
{
    public function execute(string $adminUserId, string $churchId);
}
