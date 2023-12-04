<?php

namespace App\Modules\Store\Products\Controllers;

use App\Modules\Store\Products\Contracts\CreateProductServiceInterface;
use App\Modules\Store\Products\Contracts\UpdateProductServiceInterface;
use App\Modules\Store\Products\Contracts\UpdateStatusProductsServiceInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Requests\ProductsRequest;
use App\Modules\Store\Products\Requests\ProductsUpdateStatusRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ProductsPersistenceController
{
    public function __construct(
        private CreateProductServiceInterface $createProductService,
        private UpdateProductServiceInterface $updateProductService,
        private UpdateStatusProductsServiceInterface $updateStatusProductsService,
    ) {}

    public function insert(
        ProductsRequest $request,
        ProductsDTO $dto,
    ): JsonResponse
    {
        $dto->productName        = $request->productName;
        $dto->productDescription = $request->productDescription;
        $dto->value              = $request->value;
        $dto->productCode        = $request->productCode;
        $dto->quantity           = $request->quantity;
        $dto->balance            = $request->quantity;
        $dto->highlightProduct   = $request->highlightProduct;
        $dto->categoriesId       = isset($request->categoriesId) ? $request->categoriesId : [];
        $dto->imageLinks         = isset($request->imageLinks) ? $request->imageLinks : [];

        $created = $this->createProductService->execute($dto);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function update(
        ProductsRequest $request,
        ProductsDTO $dto,
    ): JsonResponse
    {
        $dto->id                 = $request->id;
        $dto->productName        = $request->productName;
        $dto->productDescription = $request->productDescription;
        $dto->value              = $request->value;
        $dto->productCode        = $request->productCode;
        $dto->quantity           = $request->quantity;
        $dto->balance            = $request->balance;
        $dto->highlightProduct   = $request->highlightProduct;
        $dto->categoriesId       = isset($request->categoriesId) ? $request->categoriesId : [];
        $dto->imageLinks         = isset($request->imageLinks) ? $request->imageLinks : [];

        $updated = $this->updateProductService->execute($dto);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateStatus(
        ProductsUpdateStatusRequest $request,
    ): JsonResponse
    {
        $productsId = $request->productsId;

        $updated = $this->updateStatusProductsService->execute($productsId);

        return response()->json($updated, Response::HTTP_OK);
    }
}
