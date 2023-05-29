<?php

namespace App\Features\Base\Traits;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

trait UserLoggedData
{
    /**
     * @return Collection
     * @throws UserNotDefinedException
     * @throws AppException
     */
    public function getChurchesUserMember(): Collection
    {
        $user = Auth::authenticate();

        if(empty($user->member->church))
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_CHURCH,
                Response::HTTP_BAD_REQUEST
            );
        }

        $churchesMember = $user->member->church;

        return collect($churchesMember);
    }

    /**
     * @return Collection
     * @throws UserNotDefinedException
     */
    public function getModulesUserMember(): Collection
    {
        $user = Auth::authenticate();

        return collect($user->module);
    }
}
