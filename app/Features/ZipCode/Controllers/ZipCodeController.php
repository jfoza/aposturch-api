<?php

namespace App\Features\ZipCode\Controllers;

use App\Base\Http\Controllers\Controller;
use App\Features\ZipCode\Contracts\ZipCodeBusinessInterface;
use App\Features\ZipCode\Requests\ZipCodeRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ZipCodeController extends Controller
{
    public function __construct(
        private readonly ZipCodeBusinessInterface $zipCodeBusiness,
    ) {}

    public function showByZipCode(
        ZipCodeRequest $zipCodeRequest
    ): JsonResponse
    {
        $address = $this->zipCodeBusiness->findByZipCode($zipCodeRequest->zipCode);

        return response()->json($address, Response::HTTP_OK);
    }
}
