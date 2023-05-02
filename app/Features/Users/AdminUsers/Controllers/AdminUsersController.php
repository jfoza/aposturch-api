<?php

namespace App\Features\Users\AdminUsers\Controllers;

use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Requests\AdminUsersFiltersRequest;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Requests\InsertUserRequest;
use App\Features\Users\Users\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminUsersController
{
    public function __construct(
        private FindAllAdminUsersServiceInterface $adminUsersListingService,
        private ShowAdminUserServiceInterface $showAdminUserService,
        private CreateAdminUserServiceInterface $createAdminUserService,
        private UpdateAdminUserServiceInterface $updateAdminUserService,
    ) {}

    public function index(
        AdminUsersFiltersDTO $adminUsersFiltersDTO,
        AdminUsersFiltersRequest $adminUsersFiltersRequest,
    ): JsonResponse
    {
        $adminUsersFiltersDTO->paginationOrder->setColumnOrder($adminUsersFiltersRequest->columnOrder);
        $adminUsersFiltersDTO->paginationOrder->setColumnName($adminUsersFiltersRequest->columnName);
        $adminUsersFiltersDTO->paginationOrder->setPerPage($adminUsersFiltersRequest->perPage);
        $adminUsersFiltersDTO->paginationOrder->setPage($adminUsersFiltersRequest->page);

        $adminUsersFiltersDTO->name      = $adminUsersFiltersRequest->name;
        $adminUsersFiltersDTO->email     = $adminUsersFiltersRequest->email;

        $users = $this->adminUsersListingService->execute($adminUsersFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $userId = $request->id;

        $user = $this->showAdminUserService->execute($userId);

        return response()->json($user, Response::HTTP_OK);
    }

    public function insert(
        InsertUserRequest $insertUserRequest,
        UserDTO $userDTO,
    ): JsonResponse
    {
        $userDTO->name      = $insertUserRequest->name;
        $userDTO->email     = $insertUserRequest->email;
        $userDTO->password  = $insertUserRequest->password;
        $userDTO->active    = $insertUserRequest->active;
        $userDTO->profileId = $insertUserRequest->profileId;

        $newAdminUser = $this->createAdminUserService->execute($userDTO);

        return response()->json($newAdminUser, Response::HTTP_OK);
    }

    public function update(
        UpdateUserRequest $updateUserRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->id        = $updateUserRequest->id;
        $userDTO->name      = $updateUserRequest->name;
        $userDTO->email     = $updateUserRequest->email;
        $userDTO->active    = $updateUserRequest->active;

        $updated = $this->updateAdminUserService->execute($userDTO);

        return response()->json($updated, Response::HTTP_OK);
    }
}
