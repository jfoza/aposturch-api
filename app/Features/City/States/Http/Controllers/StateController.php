<?php

namespace App\Features\City\States\Http\Controllers;

use App\Features\City\States\Contracts\StateBusinessInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class StateController
{
    public function __construct(
        private StateBusinessInterface $stateBusiness,
    ) {}

    public function index(): JsonResponse
    {
        $states = $this->stateBusiness->findAll();

        return response()->json($states, Response::HTTP_OK);
    }

    public function showById(Request $response): JsonResponse
    {
        $state = $this->stateBusiness->findById($response->id);

        return response()->json($state, Response::HTTP_OK);
    }

    public function showByUF(Request $response): JsonResponse
    {
        $state = $this->stateBusiness->findByUF($response->uf);

        return response()->json($state, Response::HTTP_OK);
    }
}
