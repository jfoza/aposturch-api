<?php

namespace App\Features\Base\Services;

use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Shared\ACL\Policy;

abstract class Service
{
    use DispatchExceptionTrait;

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
