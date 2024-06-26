<?php

namespace App\Modules\Membership\Church\Controllers;

use App\Modules\Membership\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Membership\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Membership\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Requests\ChurchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ChurchPersistenceController
{
    public function __construct(
        private CreateChurchServiceInterface $createChurchService,
        private UpdateChurchServiceInterface $updateChurchService,
        private RemoveChurchServiceInterface $removeChurchService,
    ) {}

    public function insert(
        ChurchDTO $churchDTO,
        ChurchRequest $churchRequest
    ): JsonResponse
    {
        $this->extracted($churchRequest, $churchDTO);

        $created = $this->createChurchService->execute($churchDTO);

        return response()->json($created, Response::HTTP_OK);
    }

    public function update(
        ChurchDTO $churchDTO,
        ChurchRequest $churchRequest
    ): JsonResponse
    {
        $churchDTO->id = $churchRequest->id;
        $this->extracted($churchRequest, $churchDTO);

        $updated = $this->updateChurchService->execute($churchDTO);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $this->removeChurchService->execute($request->id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    private function extracted(ChurchRequest $churchRequest, ChurchDTO $churchDTO): void
    {
        $churchDTO->name           = $churchRequest->name;
        $churchDTO->phone          = $churchRequest->phone;
        $churchDTO->email          = $churchRequest->email;
        $churchDTO->youtube        = $churchRequest->youtube;
        $churchDTO->facebook       = $churchRequest->facebook;
        $churchDTO->instagram      = $churchRequest->instagram;
        $churchDTO->zipCode        = $churchRequest->zipCode;
        $churchDTO->address        = $churchRequest->address;
        $churchDTO->numberAddress  = $churchRequest->numberAddress;
        $churchDTO->complement     = $churchRequest->complement;
        $churchDTO->district       = $churchRequest->district;
        $churchDTO->uf             = $churchRequest->uf;
        $churchDTO->cityId         = $churchRequest->cityId;
        $churchDTO->active         = $churchRequest->active;
    }
}
