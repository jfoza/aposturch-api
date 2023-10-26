<?php

namespace App\Features\General\UniqueCodePrefixes\Controllers;

use App\Base\Http\Requests\FormRequest;
use App\Features\General\UniqueCodePrefixes\Contracts\CreateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\FindAllUniqueCodePrefixesServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\FindByUniqueCodePrefixIdServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\RemoveUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\Contracts\UpdateUniqueCodePrefixServiceInterface;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesDTO;
use App\Features\General\UniqueCodePrefixes\DTO\UniqueCodePrefixesFiltersDTO;
use App\Features\General\UniqueCodePrefixes\Requests\UniqueCodePrefixesFiltersRequest;
use App\Features\General\UniqueCodePrefixes\Requests\UniqueCodePrefixesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class UniqueCodePrefixesController
{
    public function __construct(
        private FindAllUniqueCodePrefixesServiceInterface $findAllUniqueCodePrefixesService,
        private FindByUniqueCodePrefixIdServiceInterface  $findByUniqueCodePrefixIdService,
        private CreateUniqueCodePrefixServiceInterface    $createUniqueCodePrefixService,
        private UpdateUniqueCodePrefixServiceInterface    $updateUniqueCodePrefixService,
        private RemoveUniqueCodePrefixServiceInterface    $removeUniqueCodePrefixService,
    ) {}

    public function index(
        UniqueCodePrefixesFiltersRequest $request,
        UniqueCodePrefixesFiltersDTO $dto
    ): JsonResponse
    {
        $dto->paginationOrder->setPage($request[FormRequest::PAGE]);
        $dto->paginationOrder->setPerPage($request[FormRequest::PER_PAGE]);
        $dto->paginationOrder->setColumnName($request[FormRequest::COLUMN_NAME]);
        $dto->paginationOrder->setColumnOrder($request[FormRequest::COLUMN_ORDER]);

        $dto->prefix = $request->prefix;

        $uniqueCodePrefixes = $this->findAllUniqueCodePrefixesService->execute($dto);

        return response()->json($uniqueCodePrefixes, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $id = $request->id;

        $uniqueCodePrefix = $this->findByUniqueCodePrefixIdService->execute($id);

        return response()->json($uniqueCodePrefix, Response::HTTP_OK);
    }

    public function insert(
        UniqueCodePrefixesRequest $request,
        UniqueCodePrefixesDTO $dto
    ): JsonResponse
    {
        $dto->prefix = $request->prefix;
        $dto->active = $request->active;

        $created = $this->createUniqueCodePrefixService->execute($dto);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function update(
        UniqueCodePrefixesRequest $request,
        UniqueCodePrefixesDTO $dto
    ): JsonResponse
    {
        $dto->id     = $request->id;
        $dto->prefix = $request->prefix;
        $dto->active = $request->active;

        $updated = $this->updateUniqueCodePrefixService->execute($dto);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeUniqueCodePrefixService->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
