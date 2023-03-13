<?php

namespace App\Features\Users\Profiles\Business;

use App\Features\Base\Business\Business;
use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\ProfilesBusinessInterface;

class ProfilesBusiness
    extends Business
    implements ProfilesBusinessInterface
{
    public function __construct(
        private readonly FindAllProfilesByUserAbilityServiceInterface $profilesByUserAbilityService
    ) {}

    public function findAll()
    {
        return $this->profilesByUserAbilityService->execute($this->getPolicy());
    }
}
