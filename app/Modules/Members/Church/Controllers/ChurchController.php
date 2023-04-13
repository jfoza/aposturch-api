<?php

namespace App\Modules\Members\Church\Controllers;

use App\Modules\Members\Church\Contracts\CreateChurchServiceInterface;
use App\Modules\Members\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Members\Church\Contracts\RemoveChurchServiceInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchUniqueNameServiceInterface;
use App\Modules\Members\Church\Contracts\UpdateChurchServiceInterface;
use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Requests\ChurchFiltersRequest;
use App\Modules\Members\Church\Requests\ChurchRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ChurchController
{
    public function __construct(
        private FindAllChurchesServiceInterface        $findAllChurchesService,
        private ShowByChurchIdServiceInterface         $showByChurchIdService,
        private ShowByChurchUniqueNameServiceInterface $showByChurchUniqueNameService,
        private CreateChurchServiceInterface           $createChurchService,
        private UpdateChurchServiceInterface           $updateChurchService,
        private RemoveChurchServiceInterface           $removeChurchService
    ) {}

    public function index(
        ChurchFiltersRequest $churchFiltersRequest,
        ChurchFiltersDTO $churchFiltersDTO
    ): JsonResponse
    {
        $churchFiltersDTO->paginationOrder->setPage($churchFiltersRequest->page);
        $churchFiltersDTO->paginationOrder->setPerPage($churchFiltersRequest->perPage);
        $churchFiltersDTO->paginationOrder->setColumnOrder($churchFiltersRequest->columnOrder);
        $churchFiltersDTO->paginationOrder->setColumnName($churchFiltersRequest->columnName);

        $churchFiltersDTO->name   = $churchFiltersRequest->name;
        $churchFiltersDTO->cityId = $churchFiltersRequest->cityId;

        $churches = $this->findAllChurchesService->execute($churchFiltersDTO);

        return response()->json($churches, Response::HTTP_OK);
    }

    public function show(Request $request): JsonResponse
    {
        $church = $this->showByChurchIdService->execute($request->id);

        return response()->json($church, Response::HTTP_OK);
    }

    public function showByUniqueName(Request $request): JsonResponse
    {
        $church = $this->showByChurchUniqueNameService->execute($request->uniqueName);

        return response()->json($church, Response::HTTP_OK);
    }

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
        $churchDTO->name          = $churchRequest->name;
        $churchDTO->phone         = $churchRequest->phone;
        $churchDTO->email         = $churchRequest->email;
        $churchDTO->youtube       = $churchRequest->youtube;
        $churchDTO->facebook      = $churchRequest->facebook;
        $churchDTO->instagram     = $churchRequest->instagram;
        $churchDTO->zipCode       = $churchRequest->zipCode;
        $churchDTO->address       = $churchRequest->address;
        $churchDTO->numberAddress = $churchRequest->numberAddress;
        $churchDTO->complement    = $churchRequest->complement;
        $churchDTO->district      = $churchRequest->district;
        $churchDTO->uf            = $churchRequest->uf;
        $churchDTO->cityId        = $churchRequest->cityId;
        $churchDTO->active        = $churchRequest->active;
    }
}
