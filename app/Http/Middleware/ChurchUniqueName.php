<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChurchUniqueName
{
    /**
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (is_null($request->uniqueName) || !is_string($request->uniqueName)) {
            throw new AppException(
                MessagesEnum::INVALID_UNIQUE_NAME,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $next($request);
    }
}
