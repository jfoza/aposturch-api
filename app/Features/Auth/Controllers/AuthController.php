<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\AuthBusinessInterface;
use App\Features\Auth\DTO\AuthDTO;
use App\Features\Auth\Requests\AuthRequest;
use App\Features\Auth\Requests\GoogleAuthRequest;
use App\Shared\Enums\AuthTypesEnum;
use App\Shared\Utils\Auth;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthController
{
    public function __construct(
        private AuthBusinessInterface $authBusiness,
    ) {}

    public function create(
        AuthDTO     $sessionsDTO,
        AuthRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->authType  = AuthTypesEnum::EMAIL_PASSWORD->value;
        $sessionsDTO->email     = $request->email;
        $sessionsDTO->password  = $request->password;
        $sessionsDTO->ipAddress = $request->ip();

        $userSession = $this->authBusiness->handle($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }

    public function createWithGoogle(
        AuthDTO           $sessionsDTO,
        GoogleAuthRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->authType        = AuthTypesEnum::GOOGLE->value;
        $sessionsDTO->googleAuthToken = $request->googleAuthToken;
        $sessionsDTO->ipAddress       = $request->ip();

        $userSession = $this->authBusiness->handle($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }

    public function destroy(): JsonResponse
    {
        Auth::logout();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
