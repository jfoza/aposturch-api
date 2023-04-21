<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\AdminUsersAuthServiceInterface;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Requests\SessionsRequest;
use App\Shared\Utils\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminUsersAuthController
{
    public function __construct(
        private AdminUsersAuthServiceInterface $adminUsersAuthService,
    ) {}

    public function create(
        SessionsDTO $sessionsDTO,
        SessionsRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->email     = $request->email;
        $sessionsDTO->password  = $request->password;
        $sessionsDTO->ipAddress = $request->ip();

        $userSession = $this->adminUsersAuthService->execute($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
