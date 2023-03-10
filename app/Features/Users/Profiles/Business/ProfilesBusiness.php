<?php

namespace App\Features\Users\Profiles\Business;

use App\Features\Base\Business\Business;
use App\Features\Users\Profiles\Contracts\ProfilesBusinessInterface;
use App\Features\Users\Profiles\Contracts\ProfilesListFactoryInterface;

class ProfilesBusiness
    extends Business
    implements ProfilesBusinessInterface
{
    public function __construct(
        private readonly ProfilesListFactoryInterface $profilesListFactory
    ) {}

    public function findAll()
    {
        return $this->profilesListFactory->execute();
    }
}
