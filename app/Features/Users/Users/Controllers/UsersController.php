<?php

namespace App\Features\Users\Users\Controllers;

use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\Users\Contracts\FindUsersByChurchServiceInterface;
use App\Features\Users\Users\DTO\UserFiltersDTO;
use App\Features\Users\Users\Requests\UserFiltersRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersController
{
    public function __construct(
        private FindUsersByChurchServiceInterface $findUsersByChurchService,
        private ShowLoggedUserServiceInterface $showLoggedUserService,
    ) {}

    public function findAllByChurch(
        UserFiltersRequest $userFiltersRequest,
        UserFiltersDTO $userFiltersDTO,
    ): JsonResponse
    {
        $userFiltersDTO->paginationOrder->setColumnOrder($userFiltersRequest->columnOrder);
        $userFiltersDTO->paginationOrder->setColumnName($userFiltersRequest->columnName);
        $userFiltersDTO->paginationOrder->setPerPage($userFiltersRequest->perPage);

        $userFiltersDTO->name     = $userFiltersRequest->name;
        $userFiltersDTO->churchId = $userFiltersRequest->id;

        $users = $this->findUsersByChurchService->execute($userFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showLoggedUserResource(): JsonResponse
    {
        $user = $this->showLoggedUserService->execute();

        return response()->json($user, Response::HTTP_OK);
    }
}