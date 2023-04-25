<?php

namespace App\Features\Users\AdminUsers\Controllers;

use App\Features\Users\AdminUsers\Contracts\CreateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllAdminUsersServiceInterface;
use App\Features\Users\AdminUsers\Contracts\FindAllByProfileUniqueNameServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowAdminUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\ShowCountAdminUsersByProfileInterface;
use App\Features\Users\AdminUsers\Contracts\ShowLoggedUserServiceInterface;
use App\Features\Users\AdminUsers\Contracts\UpdateAdminUserServiceInterface;
use App\Features\Users\AdminUsers\DTO\AdminUsersFiltersDTO;
use App\Features\Users\AdminUsers\Requests\AdminUsersFiltersRequest;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Http\Requests\InsertUserRequest;
use App\Features\Users\Users\Http\Requests\UpdateUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class AdminUsersController
{
    public function __construct(
        private FindAllAdminUsersServiceInterface $adminUsersListingService,
        private ShowAdminUserServiceInterface $showAdminUserService,
        private ShowLoggedUserServiceInterface $showLoggedUserService,
        private CreateAdminUserServiceInterface $createAdminUserService,
        private UpdateAdminUserServiceInterface $updateAdminUserService,
        private ShowCountAdminUsersByProfileInterface $showCountAdminUsersByProfile,
        private FindAllByProfileUniqueNameServiceInterface $findAllByProfileUniqueNameService,
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

        $users = $this->adminUsersListingService->execute($adminUsersFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showLoggedUserResource(): JsonResponse
    {
        $user = $this->showLoggedUserService->execute();

        return response()->json($user, Response::HTTP_OK);
    }

    public function showByProfileUniqueName(
        Request $request,
        AdminUsersFiltersDTO $adminUsersFiltersDTO
    ): JsonResponse
    {
        $adminUsersFiltersDTO->profileUniqueName = [$request->profileUniqueName];

        $users = $this->findAllByProfileUniqueNameService->execute($adminUsersFiltersDTO);

        return response()->json($users, Response::HTTP_OK);
    }

    public function showById(
        Request $request,
        AdminUsersFiltersDTO $adminUsersFiltersDTO
    ): JsonResponse
    {
        $adminUsersFiltersDTO->userId = $request->id;

        $user = $this->showAdminUserService->execute($adminUsersFiltersDTO);

        return response()->json($user, Response::HTTP_OK);
    }

    public function showCountByProfiles(): JsonResponse
    {
        $counts = $this->showCountAdminUsersByProfile->execute();

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
        $userDTO->profileId = $updateUserRequest->profileId;

        $updated = $this->updateAdminUserService->execute($userDTO);

        return response()->json($updated, Response::HTTP_OK);
    }
}
