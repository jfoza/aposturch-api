<?php

namespace App\Features\Base\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Shared\ACL\Policy;
use Illuminate\Support\Collection;

abstract class Service
{
    use DispatchExceptionTrait;

    private Policy $policy;
    private Collection $responsibleChurch;

    /**
     * @return mixed
     */
    public function getResponsibleChurch(): Collection
    {
        return $this->responsibleChurch;
    }

    /**
     * @param Collection $responsibleChurch
     */
    public function setResponsibleChurch(Collection $responsibleChurch): void
    {
        $this->responsibleChurch = $responsibleChurch;
    }

    /**
     * @throws AppException
     */
    public function userHasChurch(string $key, string $value): void
    {
        if(!$this->responsibleChurch->where($key, $value)->first())
        {
            $this->getPolicy()->dispatchErrorForbidden();
        }
    }

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
