<?php

namespace App\Modules\Store\Products\Controllers;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Products\Contracts\FindAllProductsServiceInterface;
use App\Modules\Store\Products\Contracts\ShowByProductIdServiceInterface;
use App\Modules\Store\Products\Contracts\ShowByProductUniqueNameServiceInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Requests\ProductsFiltersRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ProductsController
{
    public function __construct(
        private FindAllProductsServiceInterface $findAllProductsService,
        private ShowByProductIdServiceInterface $showByProductIdService,
        private ShowByProductUniqueNameServiceInterface $showByProductUniqueNameService,
    ) {}

    public function index(
        ProductsFiltersDTO $dto,
        ProductsFiltersRequest $request
    ): JsonResponse
    {
        $dto->paginationOrder->setPage($request[FormRequest::PAGE]);
        $dto->paginationOrder->setPerPage($request[FormRequest::PER_PAGE]);
        $dto->paginationOrder->setColumnName($request[FormRequest::COLUMN_NAME]);
        $dto->paginationOrder->setColumnOrder($request[FormRequest::COLUMN_ORDER]);

        $dto->name         = $request->name;
        $dto->nameOrCode   = $request->nameOrCode;
        $dto->categoriesId = $request->categoriesId;
        $dto->code         = $request->code;
        $dto->highlight    = isset($request->highlight) ? (bool) $request->highlight : null;
        $dto->active       = isset($request->active) ? (bool) $request->active : null;

        $products = $this->findAllProductsService->execute($dto);

        return response()->json($products, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $id = $request->id;

        $product = $this->showByProductIdService->execute($id);

        return response()->json($product, Response::HTTP_OK);
    }

    public function showByUniqueName(Request $request): JsonResponse
    {
        $uniqueName = $request->uniqueName;

        $product = $this->showByProductUniqueNameService->execute($uniqueName);

        return response()->json($product, Response::HTTP_OK);
    }
}
