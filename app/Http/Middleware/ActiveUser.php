<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ActiveUser
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $loggedUser = auth()->user();

        if(!isset($loggedUser) || !$loggedUser['active']) {
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
