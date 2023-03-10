<?php

namespace App\Features\Users\NewPasswordGenerations\Http\Controllers;

use App\Features\Users\NewPasswordGenerations\Contracts\NewPasswordGenerationsBusinessInterface;
use App\Features\Users\NewPasswordGenerations\DTO\NewPasswordGenerationsDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class NewPasswordGenerationsController
{
    public function __construct(
        private NewPasswordGenerationsBusinessInterface $newPasswordGenerationsBusiness
    ) {}

    public function update(
        Request $request,
        NewPasswordGenerationsDTO $newPasswordGenerationsDTO,
    ): JsonResponse
    {
        $newPasswordGenerationsDTO->userId = $request->id;

        $action = $this->newPasswordGenerationsBusiness->save($newPasswordGenerationsDTO);

        return response()->json($action, Response::HTTP_OK);
    }
}
