<?php

namespace App\Features\Auth\Controllers;

use App\Features\Auth\Contracts\SessionsCustomerUserBusinessInterface;
use App\Features\Auth\DTO\SessionsDTO;
use App\Features\Auth\Requests\SessionsRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class SessionsCustomerUserController
{
    public function __construct(
        private SessionsCustomerUserBusinessInterface $sessionsCustomerUserBusiness,
    ) {}

    public function create(
        SessionsDTO $sessionsDTO,
        SessionsRequest $request,
    ): JsonResponse
    {
        $sessionsDTO->email     = $request->email;
        $sessionsDTO->password  = $request->password;
        $sessionsDTO->ipAddress = $request->ip();

        $userSession = $this->sessionsCustomerUserBusiness->login($sessionsDTO);

        return response()->json($userSession, Response::HTTP_OK);
    }
}
