<?php

namespace App\Modules\Store\Categories\Controllers;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Categories\Contracts\CreateCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\FindAllCategoriesServiceInterface;
use App\Modules\Store\Categories\Contracts\FindByCategoryIdServiceInterface;
use App\Modules\Store\Categories\Contracts\RemoveCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\UpdateStatusCategoriesServiceInterface;
use App\Modules\Store\Categories\Contracts\UpdateCategoryServiceInterface;
use App\Modules\Store\Categories\DTO\CategoriesDTO;
use App\Modules\Store\Categories\DTO\CategoriesFiltersDTO;
use App\Modules\Store\Categories\Requests\CategoriesFiltersRequest;
use App\Modules\Store\Categories\Requests\CategoriesRequest;
use App\Modules\Store\Categories\Requests\CategoriesUpdateStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class CategoriesController
{
    public function __construct(
        private FindAllCategoriesServiceInterface      $findAllCategoriesService,
        private FindByCategoryIdServiceInterface       $findByCategoryIdService,
        private CreateCategoryServiceInterface         $createCategoryService,
        private UpdateCategoryServiceInterface         $updateCategoryService,
        private UpdateStatusCategoriesServiceInterface $updateStatusCategoriesService,
        private RemoveCategoryServiceInterface         $removeCategoryService,
    ) {}

    public function index(
        CategoriesFiltersRequest $request,
        CategoriesFiltersDTO     $filtersDTO,
    ): JsonResponse
    {
        $filtersDTO->paginationOrder->setPage($request[FormRequest::PAGE]);
        $filtersDTO->paginationOrder->setPerPage($request[FormRequest::PER_PAGE]);
        $filtersDTO->paginationOrder->setColumnName($request[FormRequest::COLUMN_NAME]);
        $filtersDTO->paginationOrder->setColumnOrder($request[FormRequest::COLUMN_ORDER]);

        $filtersDTO->name         = $request->name;
        $filtersDTO->departmentId = $request->departmentId;
        $filtersDTO->active       = isset($request->active) ? (bool) $request->active : null;
        $filtersDTO->hasProducts  = isset($request->hasProducts) ? (bool) $request->hasProducts : null;

        $categories = $this->findAllCategoriesService->execute($filtersDTO);

        return response()->json($categories, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $id = $request->id;

        $category = $this->findByCategoryIdService->execute($id);

        return response()->json($category, Response::HTTP_OK);
    }

    public function insert(
        CategoriesDTO        $dto,
        CategoriesRequest $request
    ): JsonResponse
    {
        $dto->departmentId = $request->departmentId;
        $dto->name         = $request->name;
        $dto->description  = $request->description;
        $dto->productsId   = $request->productsId;

        $created = $this->createCategoryService->execute($dto);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function update(
        CategoriesDTO        $dto,
        CategoriesRequest $request
    ): JsonResponse
    {
        $dto->id           = $request->id;
        $dto->departmentId = $request->departmentId;
        $dto->name         = $request->name;
        $dto->description  = $request->description;
        $dto->productsId   = isset($request->productsId) ? $request->productsId : [];

        $updated = $this->updateCategoryService->execute($dto);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateStatus(
        CategoriesUpdateStatusRequest $request,
    ): JsonResponse
    {
        $categoriesId = $request->categoriesId;

        $updated = $this->updateStatusCategoriesService->execute($categoriesId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeCategoryService->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
