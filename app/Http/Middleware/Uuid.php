<?php

namespace App\Http\Middleware;

use App\Shared\Enums\MessagesEnum;
use App\Exceptions\AppException;
use Closure;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid as UuidAlias;
use Symfony\Component\HttpFoundation\Response;

class Uuid
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws AppException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if(!UuidAlias::isValid($request->id)) {
            throw new AppException(
                MessagesEnum::INVALID_UUID,
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        return $next($request);
    }
}
