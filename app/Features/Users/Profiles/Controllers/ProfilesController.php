<?php

namespace App\Features\Users\Profiles\Controllers;

use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\Contracts\FindAllProfilesInListMembersServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProfilesController
{
    public function __construct(
        private FindAllProfilesByUserAbilityServiceInterface $findAllProfilesByUserAbilityService,
        private FindAllProfilesInListMembersServiceInterface $findAllProfilesInListMembersService,
    ) {}

    public function index(): JsonResponse
    {
        $profiles = $this->findAllProfilesByUserAbilityService->execute();

        return response()->json($profiles, Response::HTTP_OK);
    }

    public function showInListMembers(): JsonResponse
    {
        $profiles = $this->findAllProfilesInListMembersService->execute();

        return response()->json($profiles, Response::HTTP_OK);
    }
}
