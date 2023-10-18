<?php

namespace App\Modules\Store\Subcategories\Controllers;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Subcategories\Contracts\CreateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\FindAllSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Contracts\FindBySubcategoryIdServiceInterface;
use App\Modules\Store\Subcategories\Contracts\RemoveSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateStatusSubcategoriesServiceInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\DTO\SubcategoriesFiltersDTO;
use App\Modules\Store\Subcategories\Requests\SubcategoriesFiltersRequest;
use App\Modules\Store\Subcategories\Requests\SubcategoriesRequest;
use App\Modules\Store\Subcategories\Requests\SubcategoriesUpdateStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class SubcategoriesController
{
    public function __construct(
        private FindAllSubcategoriesServiceInterface      $findAllSubcategoriesService,
        private FindBySubcategoryIdServiceInterface       $findBySubcategoryIdService,
        private CreateSubcategoryServiceInterface         $createSubcategoryService,
        private UpdateSubcategoryServiceInterface         $updateSubcategoryService,
        private UpdateStatusSubcategoriesServiceInterface $updateStatusSubcategoriesService,
        private RemoveSubcategoryServiceInterface         $removeSubcategoryService,
    ) {}

    public function index(
        SubcategoriesFiltersRequest $request,
        SubcategoriesFiltersDTO $filtersDTO,
    ): JsonResponse
    {
        $filtersDTO->paginationOrder->setPage($request[FormRequest::PAGE]);
        $filtersDTO->paginationOrder->setPerPage($request[FormRequest::PER_PAGE]);
        $filtersDTO->paginationOrder->setColumnName($request[FormRequest::COLUMN_NAME]);
        $filtersDTO->paginationOrder->setColumnOrder($request[FormRequest::COLUMN_ORDER]);

        $filtersDTO->name        = $request->name;
        $filtersDTO->categoryId  = $request->categoryId;
        $filtersDTO->active      = isset($request->active) ? (bool) $request->active : null;
        $filtersDTO->hasProducts = isset($request->hasProducts) ? (bool) $request->hasProducts : null;

        $subcategories = $this->findAllSubcategoriesService->execute($filtersDTO);

        return response()->json($subcategories, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $id = $request->id;

        $subcategory = $this->findBySubcategoryIdService->execute($id);

        return response()->json($subcategory, Response::HTTP_OK);
    }

    public function insert(
        SubcategoriesDTO $dto,
        SubcategoriesRequest $request
    ): JsonResponse
    {
        $dto->categoryId  = $request->categoryId;
        $dto->name        = $request->name;
        $dto->description = $request->description;
        $dto->productsId  = $request->productsId;

        $created = $this->createSubcategoryService->execute($dto);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function update(
        SubcategoriesDTO $dto,
        SubcategoriesRequest $request
    ): JsonResponse
    {
        $dto->id          = $request->id;
        $dto->categoryId  = $request->categoryId;
        $dto->name        = $request->name;
        $dto->description = $request->description;
        $dto->productsId  = $request->productsId;

        $updated = $this->updateSubcategoryService->execute($dto);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateStatus(
        SubcategoriesUpdateStatusRequest $request,
    ): JsonResponse
    {
        $subcategoriesId = $request->subcategoriesId;

        $updated = $this->updateStatusSubcategoriesService->execute($subcategoriesId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeSubcategoryService->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
