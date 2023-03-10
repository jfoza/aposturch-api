<?php

namespace App\Features\Users\CustomerUsers\Services\Utils;

use App\Shared\Helpers\RandomStringHelper;
use App\Features\Users\CustomerUsers\Http\Requests\CustomerUsersRequest;
use App\Features\Users\CustomerUsers\Http\Requests\InsertCustomerUserRequest;
use App\Features\Users\CustomerUsers\Http\Requests\UpdateCustomerUserRequest;
use App\Features\Users\Users\DTO\UserDTO;

class ExtractCustomerUserRequestService
{
    public static function extractedCustomerUserRequest(
        CustomerUsersRequest $customerUsersRequest,
        UserDTO $userDTO,
    ): void
    {
        self::extracted($customerUsersRequest, $userDTO);
        $userDTO->password = strtolower(RandomStringHelper::alnumGenerate(6));
    }

    public static function extractedPublicCustomerUserRequest(
        InsertCustomerUserRequest|UpdateCustomerUserRequest $publicCustomerUsersRequest,
        UserDTO $userDTO,
    ): void
    {
        self::extracted($publicCustomerUsersRequest, $userDTO);
        $userDTO->password = $publicCustomerUsersRequest->password;
    }

    private static function extracted(
        CustomerUsersRequest|InsertCustomerUserRequest|UpdateCustomerUserRequest
        $customerUsersRequest,
        UserDTO $userDTO
    ): void
    {
        $userDTO->name                  = $customerUsersRequest->name;
        $userDTO->email                 = $customerUsersRequest->email;
        $userDTO->active                = $customerUsersRequest->active;
        $userDTO->person->phone         = $customerUsersRequest->phone;
        $userDTO->person->zipCode       = $customerUsersRequest->zipCode;
        $userDTO->person->address       = $customerUsersRequest->address;
        $userDTO->person->numberAddress = $customerUsersRequest->numberAddress;
        $userDTO->person->complement    = $customerUsersRequest->complement;
        $userDTO->person->district      = $customerUsersRequest->district;
        $userDTO->person->cityId        = $customerUsersRequest->cityId;
        $userDTO->person->uf            = strtoupper($customerUsersRequest->uf);
    }
}
