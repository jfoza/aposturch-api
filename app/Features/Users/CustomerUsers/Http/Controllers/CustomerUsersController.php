<?php

namespace App\Features\Users\CustomerUsers\Http\Controllers;

use App\Features\Users\CustomerUsers\Contracts\CustomerUsersBusinessInterface;
use App\Features\Users\CustomerUsers\DTO\CustomerUsersFiltersDTO;
use App\Features\Users\CustomerUsers\Http\Requests\CustomerUsersFiltersRequest;
use App\Features\Users\CustomerUsers\Http\Requests\CustomerUsersRequest;
use App\Features\Users\CustomerUsers\Services\Utils\ExtractCustomerUserRequestService;
use App\Features\Users\Users\DTO\UserDTO;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class CustomerUsersController
{
    public function __construct(
        private CustomerUsersBusinessInterface $customerUsersBusiness
    ) {}

    public function index(
        CustomerUsersFiltersRequest $customerUsersFiltersRequest,
        CustomerUsersFiltersDTO $customerUsersFiltersDTO
    ): JsonResponse
    {
        $customerUsersFiltersDTO->paginationOrder->setColumnOrder($customerUsersFiltersRequest->columnOrder);
        $customerUsersFiltersDTO->paginationOrder->setColumnName($customerUsersFiltersRequest->columnName);
        $customerUsersFiltersDTO->paginationOrder->setPerPage($customerUsersFiltersRequest->perPage);

        $customerUsersFiltersDTO->name   = $customerUsersFiltersRequest->personName;
        $customerUsersFiltersDTO->email  = $customerUsersFiltersRequest->userEmail;
        $customerUsersFiltersDTO->city   = $customerUsersFiltersRequest->personCity;
        $customerUsersFiltersDTO->active = $customerUsersFiltersRequest->userActive;

        $customerUsers = $this->customerUsersBusiness->findAll($customerUsersFiltersDTO);

        return response()->json($customerUsers, Response::HTTP_OK);
    }

    public function showById(Request $request): JsonResponse
    {
        $customerUserId = $this->customerUsersBusiness->findById($request->id);

        return response()->json($customerUserId, Response::HTTP_OK);
    }

    public function insert(
        CustomerUsersRequest $customerUsersRequest,
        UserDTO $userDTO,
    ): JsonResponse
    {
        ExtractCustomerUserRequestService::extractedCustomerUserRequest($customerUsersRequest, $userDTO);

        $customerUserCreated = $this->customerUsersBusiness->create($userDTO);

        return response()->json($customerUserCreated, Response::HTTP_OK);
    }

    public function update(
        CustomerUsersRequest $customerUsersRequest,
        UserDTO $userDTO,
    ): JsonResponse
    {
        $userDTO->id = $customerUsersRequest->id;

        ExtractCustomerUserRequestService::extractedCustomerUserRequest($customerUsersRequest, $userDTO);

        $customerUserCreated = $this->customerUsersBusiness->save($userDTO);

        return response()->json($customerUserCreated, Response::HTTP_OK);
    }
}
