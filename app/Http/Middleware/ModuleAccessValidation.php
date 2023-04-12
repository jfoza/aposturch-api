<?php

namespace App\Http\Middleware;

use App\Exceptions\AppException;
use App\Features\Base\Traits\PolicyGenerationTrait;
use App\Shared\Enums\MessagesEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ModuleAccessValidation
{
    use PolicyGenerationTrait;

    /**
     * @throws AppException
     */
    public function handle(Request $request, Closure $next, string $ability): Response
    {
        $policy = $this->generatePolicyUser();

        $policy->havePermission($ability, true);

        return $next($request);
    }
}
