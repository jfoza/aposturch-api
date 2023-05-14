<?php

namespace App\Features\Base\Business;

use App\Shared\ACL\Policy;

abstract class Business
{
    private Policy $policy;

    /**
     * @return Policy
     */
    public function getPolicy(): Policy
    {
        return $this->policy;
    }

    /**
     * @param Policy $policy
     */
    public function setPolicy(Policy $policy): void
    {
        $this->policy = $policy;
    }
}
