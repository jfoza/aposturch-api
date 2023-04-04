<?php

namespace App\Features\Users\AdminUsers\Http\Controllers;

use App\Features\Users\AdminUsers\Contracts\AdminUsersBusinessInterface;
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
        private AdminUsersBusinessInterface $adminUsersBusiness
    ) {}

    public function index(
        AdminUsersFiltersDTO $adminUsersFiltersDTO,
        AdminUsersFiltersRequest $adminUsersFiltersRequest,
    ): JsonResponse
    {
        $adminUsersFiltersDTO->paginationOrder->setColumnOrder($adminUsersFiltersRequest->columnOrder);
        $adminUsersFiltersDTO->paginationOrder->setColumnName($adminUsersFiltersRequest->columnName);
        $adminUsersFiltersDTO->paginationOrder->setPerPage($adminUsersFiltersRequest->perPage);

        $adminUsersFiltersDTO->name      = $adminUsersFiltersRequest->name;
        $adminUsersFiltersDTO->profileId = $adminUsersFiltersRequest->profileId;
        $adminUsersFiltersDTO->email     = $adminUsersFiltersRequest->email;

        $users = $this->adminUsersBusiness->findAll($adminUsersFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showLoggedUserResource(): JsonResponse
    {
        $user = $this->adminUsersBusiness->findLoggedUser();

        return response()->json($user, Response::HTTP_OK);
    }

    public function showById(
        Request $request,
        AdminUsersFiltersDTO $adminUsersFiltersDTO
    ): JsonResponse
    {
        $adminUsersFiltersDTO->userId = $request->id;

        $user = $this->adminUsersBusiness->findByUserId($adminUsersFiltersDTO);

        return response()->json($user, Response::HTTP_OK);
    }

    public function showCountByProfiles(): JsonResponse
    {
        $counts = $this->adminUsersBusiness->findCountByProfiles();

        return response()->json($counts, Response::HTTP_OK);
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

        $newAdminUser = $this->adminUsersBusiness->create($userDTO);

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

        $updated = $this->adminUsersBusiness->save($userDTO);

        return response()->json($updated, Response::HTTP_OK);
    }
}
