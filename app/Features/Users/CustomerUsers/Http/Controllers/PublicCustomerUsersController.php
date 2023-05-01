<?php

namespace App\Features\Users\CustomerUsers\Http\Controllers;

use App\Features\Users\CustomerUsers\Contracts\PublicCustomerUsersBusinessInterface;
use App\Features\Users\CustomerUsers\Http\Requests\AuthorizeCustomerUserRequest;
use App\Features\Users\CustomerUsers\Http\Requests\InsertCustomerUserRequest;
use App\Features\Users\CustomerUsers\Http\Requests\UpdateCustomerUserRequest;
use App\Features\Users\CustomerUsers\Services\Utils\ExtractCustomerUserRequestService;
use App\Features\Users\Users\DTO\PasswordDTO;
use App\Features\Users\Users\DTO\UserDTO;
use App\Features\Users\Users\Requests\PasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class PublicCustomerUsersController
{
    public function __construct(
        private PublicCustomerUsersBusinessInterface $publicCustomerUsersBusiness
    ) {}

    public function show(): JsonResponse
    {
        $customerUserId = $this->publicCustomerUsersBusiness->findById();

        return response()->json($customerUserId, Response::HTTP_OK);
    }
    public function emailExists(Request $request): JsonResponse
    {
        $this->publicCustomerUsersBusiness->verifyEmailExists($request->email);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function insert(
        InsertCustomerUserRequest $insertCustomerUserRequest,
        UserDTO $userDTO,
    ): JsonResponse
    {
        ExtractCustomerUserRequestService::extractedPublicCustomerUserRequest($insertCustomerUserRequest, $userDTO);

        $customerUserCreated = $this->publicCustomerUsersBusiness->create($userDTO);

        return response()->json($customerUserCreated, Response::HTTP_OK);
    }

    public function update(
        UpdateCustomerUserRequest $updateCustomerUserRequest,
        UserDTO $userDTO,
    ): JsonResponse
    {
        ExtractCustomerUserRequestService::extractedPublicCustomerUserRequest($updateCustomerUserRequest, $userDTO);

        $customerUserCreated = $this->publicCustomerUsersBusiness->save($userDTO);

        return response()->json($customerUserCreated, Response::HTTP_OK);
    }

    public function authorizeCustomerUser(
        AuthorizeCustomerUserRequest $authorizeCustomerUserRequest
    ): JsonResponse
    {
        $this->publicCustomerUsersBusiness->authorizeCustomerUser(
            $authorizeCustomerUserRequest->id,
            $authorizeCustomerUserRequest->code,
        );

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function resendEmail(
        Request $request,
    ): JsonResponse
    {
        $this->publicCustomerUsersBusiness->resendEmailNewCustomerUser($request->email);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    public function updateCustomerUserPassword(
        PasswordRequest $passwordRequest,
        PasswordDTO $passwordDTO
    ): JsonResponse
    {
        $passwordDTO->currentPassword = $passwordRequest->currentPassword;
        $passwordDTO->newPassword     = $passwordRequest->newPassword;

        $this->publicCustomerUsersBusiness->saveNewPassword($passwordDTO);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
