<?php

namespace App\Features\Users\Profiles\Contracts;

use App\Shared\ACL\Policy;

interface FindAllProfilesInListMembersServiceInterface
{
    public function execute();
}
