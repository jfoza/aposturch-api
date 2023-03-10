<?php

namespace App\Features\Users\Profiles\Http\Controllers;

use App\Features\Users\Profiles\Contracts\ProfilesBusinessInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProfilesController
{
    public function __construct(
        private ProfilesBusinessInterface $profilesBusiness,
    ) {}

    public function index(): JsonResponse
    {
        $profiles = $this->profilesBusiness->findAll();

        return response()->json($profiles, Response::HTTP_OK);
    }
}
