<?php

namespace App\Modules\Membership\Church\Contracts;

interface RemoveResponsibleChurchRelationshipServiceInterface
{
    public function execute(string $adminUserId, string $churchId);
}
