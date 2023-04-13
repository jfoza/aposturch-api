<?php

namespace App\Modules\Members\Church\Controllers;

use App\Modules\Members\Church\Contracts\FindAllChurchesServiceInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchIdServiceInterface;
use App\Modules\Members\Church\Contracts\ShowByChurchUniqueNameServiceInterface;
use App\Modules\Members\Church\DTO\ChurchFiltersDTO;
use App\Modules\Members\Church\Requests\ChurchFiltersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ChurchListController
{
    public function __construct(
        private FindAllChurchesServiceInterface        $findAllChurchesService,
        private ShowByChurchIdServiceInterface         $showByChurchIdService,
        private ShowByChurchUniqueNameServiceInterface $showByChurchUniqueNameService,
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
}
