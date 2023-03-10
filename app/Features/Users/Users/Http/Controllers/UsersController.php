<?php

namespace App\Features\Users\Users\Http\Controllers;

use App\Features\Users\Users\Contracts\UsersBusinessInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersController
{
    public function __construct(
        private UsersBusinessInterface $usersBusiness,
    ) {}

    public function showById(Request $request): JsonResponse
    {
        $user = $this->usersBusiness->findById($request->id);

        return response()->json($user, Response::HTTP_OK);
    }
}
