<?php

namespace App\Http\Middleware;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Email
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            throw new AppException(
                MessagesEnum::INVALID_EMAIL,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $next($request);
    }
}
