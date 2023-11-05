<?php

namespace App\Base\Services;

use App\Shared\ACL\Policy;

abstract class BaseService
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
