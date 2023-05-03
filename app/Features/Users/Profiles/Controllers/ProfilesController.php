<?php

namespace App\Features\Users\Profiles\Controllers;

use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProfilesController
{
    public function __construct(
        private FindAllProfilesByUserAbilityServiceInterface $findAllProfilesByUserAbilityService,
    ) {}

    public function index(): JsonResponse
    {
        $profiles = $this->findAllProfilesByUserAbilityService->execute();

        return response()->json($profiles, Response::HTTP_OK);
    }
}
