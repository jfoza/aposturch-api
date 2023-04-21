<?php

namespace App\Features\City\Cities\Http\Controllers;

use App\Features\City\Cities\Contracts\CityBusinessInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class CityController
{
    public function __construct(
        private CityBusinessInterface $cityBusiness,
    ) {}

    public function showByUF(Request $response): JsonResponse
    {
        $cities = $this->cityBusiness->findByUF($response->uf);

        return response()->json($cities, Response::HTTP_OK);
    }

    public function showById(Request $response): JsonResponse
    {
        $city = $this->cityBusiness->findById($response->id);

        return response()->json($city, Response::HTTP_OK);
    }

    public function showInPersons(): JsonResponse
    {
        $cities = $this->cityBusiness->findAllInPersons();

        return response()->json($cities, Response::HTTP_OK);
    }

    public function showInChurches(): JsonResponse
    {
        $cities = $this->cityBusiness->findAllInChurches();

        return response()->json($cities, Response::HTTP_OK);
    }
}
