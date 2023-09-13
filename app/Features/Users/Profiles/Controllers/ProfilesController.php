<?php

namespace App\Features\Users\Profiles\Controllers;

use App\Features\Users\Profiles\Contracts\FindAllProfilesByUserAbilityServiceInterface;
use App\Features\Users\Profiles\DTO\ProfilesFiltersDTO;
use App\Features\Users\Profiles\Requests\ProfilesFiltersRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProfilesController
{
    public function __construct(
        private FindAllProfilesByUserAbilityServiceInterface $findAllProfilesByUserAbilityService,
    ) {}

    public function index(
        ProfilesFiltersRequest $profilesFiltersRequest,
        ProfilesFiltersDTO $profilesFiltersDTO
    ): JsonResponse
    {
        $profilesFiltersDTO->profileTypeUniqueName = $profilesFiltersRequest->profileTypeUniqueName;

        $profiles = $this->findAllProfilesByUserAbilityService->execute($profilesFiltersDTO);

        return response()->json($profiles, Response::HTTP_OK);
    }
}
