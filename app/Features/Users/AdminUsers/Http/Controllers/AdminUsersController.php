<?php

namespace App\Features\Users\AdminUsers\Http\Controllers;

use App\Features\Users\AdminUsers\Contracts\AdminUsersListBusinessInterface;
use App\Features\Users\AdminUsers\Contracts\AdminUsersPersistenceBusinessInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Http\Requests\AdminUsersFiltersRequest;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Http\Requests\InsertUserRequest;
use App\Features\Users\Users\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminUsersController
{
    public function __construct(
        private AdminUsersListBusinessInterface        $adminUsersListBusiness,
        private AdminUsersPersistenceBusinessInterface $adminUsersPersistenceBusiness,
    ) {}

    public function index(
        AdminUsersFiltersDTO $adminUsersFiltersDTO,
        AdminUsersFiltersRequest $adminUsersFiltersRequest,
    ): JsonResponse
    {
        $adminUsersFiltersDTO->paginationOrder->setColumnOrder($adminUsersFiltersRequest->columnOrder);
        $adminUsersFiltersDTO->paginationOrder->setColumnName($adminUsersFiltersRequest->columnName);
        $adminUsersFiltersDTO->paginationOrder->setPerPage($adminUsersFiltersRequest->perPage);

        $adminUsersFiltersDTO->name  = $adminUsersFiltersRequest->name;
        $adminUsersFiltersDTO->email = $adminUsersFiltersRequest->email;

        $users = $this->adminUsersListBusiness->findAll($adminUsersFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showLoggedUserResource(): JsonResponse
    {
        $user = $this->adminUsersListBusiness->findLoggedUser(true);

        return response()->json($user, Response::HTTP_OK);
    }

    public function showById(
        Request $request,
        AdminUsersFiltersDTO $adminUsersFiltersDTO
    ): JsonResponse
    {
        $adminUsersFiltersDTO->userId = $request->id;

        $user = $this->adminUsersListBusiness->findByUserId($adminUsersFiltersDTO);

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

        $newAdminUser = $this->adminUsersPersistenceBusiness->create($userDTO);

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
        $userDTO->profileId = $updateUserRequest->profileId;

        $updated = $this->adminUsersPersistenceBusiness->save($userDTO);

        return response()->json($updated, Response::HTTP_OK);
    }
}
