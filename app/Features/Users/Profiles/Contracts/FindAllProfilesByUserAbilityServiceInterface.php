<?php

namespace App\Features\Users\Profiles\Contracts;

use App\Features\Users\Profiles\DTO\ProfilesFiltersDTO;
use App\Shared\ACL\Policy;

interface FindAllProfilesByUserAbilityServiceInterface
{
    public function execute(ProfilesFiltersDTO $profilesFiltersDTO);
}
