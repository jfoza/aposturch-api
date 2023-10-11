<?php

namespace App\Http\Middleware;

use App\Base\Traits\PolicyGenerationTrait;
use App\Exceptions\AppException;
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
