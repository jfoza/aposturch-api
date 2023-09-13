<?php

namespace App\Modules\Membership\Members\Controllers;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Contracts\UpdateStatusMemberServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Requests\MembersFiltersRequest;
use App\Modules\Membership\Members\Requests\MembersRequest;
use App\Shared\Helpers\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class MembersController
{
    public function __construct(
        private FindAllMembersServiceInterface $findAllMembersService,
        private ShowByUserIdServiceInterface $showByUserIdService,
        private CreateMemberServiceInterface $createMemberService,
        private UpdateStatusMemberServiceInterface $updateStatusMemberService,
    ) {}

    public function index(
        MembersFiltersRequest $membersFiltersRequest,
        MembersFiltersDTO $membersFiltersDTO
    ): JsonResponse
    {
        $membersFiltersDTO->paginationOrder->setPage($membersFiltersRequest->page);
        $membersFiltersDTO->paginationOrder->setPerPage($membersFiltersRequest->perPage);
        $membersFiltersDTO->paginationOrder->setColumnName($membersFiltersRequest->columnName);
        $membersFiltersDTO->paginationOrder->setColumnOrder($membersFiltersRequest->columnOrder);

        $membersFiltersDTO->name                 = $membersFiltersRequest->name;
        $membersFiltersDTO->email                = $membersFiltersRequest->email;
        $membersFiltersDTO->phone                = Helpers::onlyNumbers($membersFiltersRequest->phone);
        $membersFiltersDTO->churchIdInQueryParam = $membersFiltersRequest->churchId;
        $membersFiltersDTO->profileId            = $membersFiltersRequest->profileId;
        $membersFiltersDTO->cityId               = $membersFiltersRequest->cityId;

        $members = $this->findAllMembersService->execute($membersFiltersDTO);

        return response()->json($members, Response::HTTP_OK);
    }

    public function showByUserId(
        Request $request,
    ): JsonResponse
    {
        $userId = $request->id;

        $member = $this->showByUserIdService->execute($userId);

        return response()->json($member, Response::HTTP_OK);
    }

    public function insert(
        MembersRequest $membersRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->name      = $membersRequest->name;
        $userDTO->email     = $membersRequest->email;
        $userDTO->password  = $membersRequest->password;
        $userDTO->profileId = $membersRequest->profileId;
        $userDTO->modulesId = $membersRequest->modulesId;

        $userDTO->member->churchId      = $membersRequest->churchId;

        $userDTO->person->phone         = Helpers::onlyNumbers($membersRequest->phone);
        $userDTO->person->zipCode       = Helpers::onlyNumbers($membersRequest->zipCode);
        $userDTO->person->address       = $membersRequest->address;
        $userDTO->person->numberAddress = $membersRequest->numberAddress;
        $userDTO->person->complement    = $membersRequest->complement;
        $userDTO->person->district      = $membersRequest->district;
        $userDTO->person->cityId        = $membersRequest->cityId;
        $userDTO->person->uf            = $membersRequest->uf;

        $created = $this->createMemberService->execute($userDTO);

        return response()->json($created, Response::HTTP_OK);
    }

    public function updateStatus(Request $request): JsonResponse
    {
        $userId = $request->id;

        $status = $this->updateStatusMemberService->execute($userId);

        return response()->json($status, Response::HTTP_OK);
    }
}
