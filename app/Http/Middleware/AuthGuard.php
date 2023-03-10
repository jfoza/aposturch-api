<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Utils\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\UserNotDefinedException;

class AuthGuard
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        try
        {
            Auth::authenticate();

            return $next($request);
        }
        catch (UserNotDefinedException)
        {
            throw new AppException(
                MessagesEnum::UNAUTHORIZED,
                Response::HTTP_UNAUTHORIZED
            );
        }
    }
}
