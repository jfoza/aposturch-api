<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Features\Users\Profiles\Enums\ProfileUniqueNameEnum;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateProfileUniqueName
{
    /**
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $profiles = array_column(ProfileUniqueNameEnum::cases(), 'value');

        if(!in_array($request->profileUniqueName, $profiles))
        {
            throw new AppException(
                MessagesEnum::INVALID_PROFILE,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $next($request);
    }
}
