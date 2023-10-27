<?php

namespace App\Modules\Store\Departments\Controllers;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Departments\Contracts\CreateDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\FindAllDepartmentsServiceInterface;
use App\Modules\Store\Departments\Contracts\FindByDepartmentIdServiceInterface;
use App\Modules\Store\Departments\Contracts\RemoveDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\UpdateDepartmentServiceInterface;
use App\Modules\Store\Departments\Contracts\UpdateStatusDepartmentsServiceInterface;
use App\Modules\Store\Departments\DTO\DepartmentsDTO;
use App\Modules\Store\Departments\DTO\DepartmentsFiltersDTO;
use App\Modules\Store\Departments\Requests\DepartmentsFiltersRequest;
use App\Modules\Store\Departments\Requests\DepartmentsRequest;
use App\Modules\Store\Departments\Requests\DepartmentsUpdateStatusRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class DepartmentsController
{
    public function __construct(
        private FindAllDepartmentsServiceInterface      $findAllDepartmentsService,
        private FindByDepartmentIdServiceInterface      $findByDepartmentIdService,
        private CreateDepartmentServiceInterface        $createDepartmentService,
        private UpdateDepartmentServiceInterface        $updateDepartmentService,
        private UpdateStatusDepartmentsServiceInterface $updateStatusDepartmentService,
        private RemoveDepartmentServiceInterface        $removeDepartmentService,
    ) {}

    public function index(
        DepartmentsFiltersRequest $request,
        DepartmentsFiltersDTO     $filtersDTO,
    ): JsonResponse
    {
        $filtersDTO->paginationOrder->setPage($request[FormRequest::PAGE]);
        $filtersDTO->paginationOrder->setPerPage($request[FormRequest::PER_PAGE]);
        $filtersDTO->paginationOrder->setColumnName($request[FormRequest::COLUMN_NAME]);
        $filtersDTO->paginationOrder->setColumnOrder($request[FormRequest::COLUMN_ORDER]);

        $filtersDTO->name             = $request->name;
        $filtersDTO->active           = isset($request->active) ? (bool) $request->active : null;
        $filtersDTO->hasSubcategories = isset($request->hasSubcategories) ? (bool) $request->hasSubcategories : null;

        $departments = $this->findAllDepartmentsService->execute($filtersDTO);

        return response()->json($departments, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $id = $request->id;

        $department = $this->findByDepartmentIdService->execute($id);

        return response()->json($department, Response::HTTP_OK);
    }

    public function insert(
        DepartmentsRequest $request,
        DepartmentsDTO     $dto,
    ): JsonResponse
    {
        $dto->name            = $request->name;
        $dto->description     = $request->description;

        $created = $this->createDepartmentService->execute($dto);

        return response()->json($created, Response::HTTP_CREATED);
    }

    public function update(
        DepartmentsRequest $request,
        DepartmentsDTO     $dto,
    ): JsonResponse
    {
        $dto->id              = $request->id;
        $dto->name            = $request->name;
        $dto->description     = $request->description;

        $updated = $this->updateDepartmentService->execute($dto);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function updateStatus(
        DepartmentsUpdateStatusRequest $request,
    ): JsonResponse
    {
        $departmentsId = $request->departmentsId;

        $updated = $this->updateStatusDepartmentService->execute($departmentsId);

        return response()->json($updated, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeDepartmentService->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
