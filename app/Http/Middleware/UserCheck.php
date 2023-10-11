<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserCheck
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::getUser();

        $session = collect($user->session)->where('token', $request->bearerToken())->first();

        if($session && $session->active === false)
        {
            auth()->logout();

            throw new AppException(
                MessagesEnum::UNAUTHORIZED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if(!isset($user) || !$user->active) {
            throw new AppException(
                [
                    'message' => MessagesEnum::INACTIVE_USER,
                    'inactiveUser' => true
                ],
                Response::HTTP_FORBIDDEN
            );
        }

        return $next($request);
    }
}
