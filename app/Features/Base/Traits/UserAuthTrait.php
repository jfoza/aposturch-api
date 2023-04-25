<?php

namespace App\Features\Base\Traits;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

trait UserAuthTrait
{
    /**
     * @throws UserNotDefinedException
     * @throws AppException
     */
    public function getResponsibleChurch(): Collection
    {
        $user = Auth::authenticate();


        if(!$responsibleChurch = $user->adminUser->responsibleChurch)
        {
            throw new AppException(
                MessagesEnum::USER_HAS_NO_CHURCH,
                Response::HTTP_BAD_REQUEST
            );
        }

        return collect($responsibleChurch);
    }
}
