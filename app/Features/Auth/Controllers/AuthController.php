<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Requests\SessionsRequest;
use App\Shared\Utils\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthController
{
    public function __construct(
        private AuthBusinessInterface $authBusiness,
    ) {}

    public function create(
        AuthDTO         $sessionsDTO,
        SessionsRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->email     = $request->email;
        $sessionsDTO->password  = $request->password;
        $sessionsDTO->ipAddress = $request->ip();

        $userSession = $this->authBusiness->authenticate($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
