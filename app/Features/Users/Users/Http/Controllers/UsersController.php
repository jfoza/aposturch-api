<?php

namespace App\Features\Users\Users\Http\Controllers;

use App\Features\Users\Users\Contracts\FindAllUsersServiceInterface;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Features\Users\Users\Http\Requests\UserFiltersRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersController
{
    public function __construct(
        private FindAllUsersServiceInterface $findAllUsersService,
    ) {}

    public function index(
        UserFiltersRequest $userFiltersRequest,
        UserFiltersDTO $userFiltersDTO,
    ): JsonResponse
    {
        $userFiltersDTO->paginationOrder->setColumnOrder($userFiltersRequest->columnOrder);
        $userFiltersDTO->paginationOrder->setColumnName($userFiltersRequest->columnName);
        $userFiltersDTO->paginationOrder->setPerPage($userFiltersRequest->perPage);

        $userFiltersDTO->name     = $userFiltersRequest->name;
        $userFiltersDTO->churchId = $userFiltersRequest->churchId;

        $users = $this->findAllUsersService->execute($userFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }
}
