<?php

namespace App\Features\Base\Services;

use App\Exceptions\AppException;
use App\Features\Base\Traits\DispatchExceptionTrait;
use App\Shared\ACL\Policy;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

abstract class Service
{
    use DispatchExceptionTrait;

    private Policy $policy;

    /**
     * @return Collection
     * @throws UserNotDefinedException
     * @throws AppException
     */
    public function getChurchesUserMember(): Collection
    {
        $user = Auth::authenticate();

        if(!$churchesMember = $user->member->church)
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_CHURCH,
                Response::HTTP_BAD_REQUEST
            );
        }

        return collect($churchesMember);
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
