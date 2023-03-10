<?php

namespace App\Features\Base\Business;

use App\Shared\ACL\Policy;
use App\Features\Base\Traits\DispatchExceptionTrait;

abstract class Business
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
