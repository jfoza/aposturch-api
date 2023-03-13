<?php

namespace App\Features\Users\Profiles\Contracts;

use App\Shared\ACL\Policy;

interface FindAllProfilesByUserAbilityServiceInterface
{
    public function execute(Policy $policy);
}
