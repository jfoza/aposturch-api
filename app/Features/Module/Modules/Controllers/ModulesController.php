<?php

namespace App\Features\Module\Modules\Controllers;

use App\Features\Module\Modules\Contracts\FindAllModulesByUserLoggedServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ModulesController
{
    public function __construct(
        private FindAllModulesByUserLoggedServiceInterface $findAllModulesByUserLoggedService
    ) {}

    public function showByUserLogged(): JsonResponse
    {
        $modules = $this->findAllModulesByUserLoggedService->execute();

        return response()->json($modules, Response::HTTP_OK);
    }
}
