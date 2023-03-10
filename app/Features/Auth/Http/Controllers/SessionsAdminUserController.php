<?php

namespace App\Features\Auth\Http\Controllers;

use App\Shared\Utils\Auth;
use App\Features\Auth\Contracts\SessionsAdminUserBusinessInterface;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Http\Requests\SessionsRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class SessionsAdminUserController
{
    public function __construct(
        private SessionsAdminUserBusinessInterface $sessionsAdminUserBusiness,
    ) {}

    public function create(
        SessionsDTO $sessionsDTO,
        SessionsRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->email     = $request->email;
        $sessionsDTO->password  = $request->password;
        $sessionsDTO->ipAddress = $request->ip();

        $userSession = $this->sessionsAdminUserBusiness->login($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
