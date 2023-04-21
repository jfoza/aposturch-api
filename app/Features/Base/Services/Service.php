<?php

namespace App\Features\Base\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Modules\Members\Church\Models\Church;
use App\Shared\ACL\Policy;
use Illuminate\Support\Collection;

abstract class Service
{
    use DispatchExceptionTrait;

    private Policy $policy;
    private Collection $churchesUserAuth;

    /**
     * @return mixed
     */
    public function getChurchesUserAuth(): Collection
    {
        return $this->churchesUserAuth;
    }

    /**
     * @param mixed $churchesUserAuth
     */
    public function setChurchesUserAuth(Collection $churchesUserAuth): void
    {
        $this->churchesUserAuth = $churchesUserAuth;
    }

    /**
     * @throws AppException
     */
    public function userHasChurch(string $key, string $value): void
    {
        if(!$this->churchesUserAuth->where($key, $value)->first())
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
