<?php

namespace App\Features\Users\Users\Controllers;

use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\Users\Contracts\UpdateStatusUserServiceInterface;
use App\Features\Users\Users\Contracts\UserEmailAlreadyExistsServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersController
{
    public function __construct(
        private ShowLoggedUserServiceInterface $showLoggedUserService,
        private UpdateStatusUserServiceInterface $updateStatusUserService,
        private UserEmailAlreadyExistsServiceInterface $userEmailAlreadyExistsService,
    ) {}

    public function showLoggedUserResource(): JsonResponse
    {
        $user = $this->showLoggedUserService->execute();

        return response()->json($user, Response::HTTP_OK);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $userId = $request->id;

        $status = $this->updateStatusUserService->execute($userId);

        return response()->json($status, Response::HTTP_OK);
    }

    public function userEmailAlreadyExists(Request $request): JsonResponse
    {
        $email = $request->email;

        $this->userEmailAlreadyExistsService->execute($email);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
