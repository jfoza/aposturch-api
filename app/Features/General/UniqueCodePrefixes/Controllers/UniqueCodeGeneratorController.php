<?php

namespace App\Features\General\UniqueCodePrefixes\Controllers;

use App\Features\General\UniqueCodePrefixes\Contracts\UniqueCodeGeneratorBusinessInterface;
use App\Features\General\UniqueCodePrefixes\Requests\UniqueCodeGeneratorRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class UniqueCodeGeneratorController
{
    public function __construct(
        private UniqueCodeGeneratorBusinessInterface $uniqueCodeGeneratorBusiness,
    ) {}

    public function generateUniqueCode(
        UniqueCodeGeneratorRequest $request
    ): JsonResponse
    {
        $uniqueCodeType = $request->uniqueCodeType;

        $code = $this->uniqueCodeGeneratorBusiness->handle($uniqueCodeType);

        return response()->json($code, Response::HTTP_OK);
    }
}
