<?php

namespace App\Modules\Membership\Members\Controllers;

use App\Features\Users\Users\DTO\UserDTO;
use App\Modules\Membership\Members\Contracts\CreateMemberServiceInterface;
use App\Modules\Membership\Members\Contracts\FindAllMembersServiceInterface;
use App\Modules\Membership\Members\Contracts\ShowByUserIdServiceInterface;
use App\Modules\Membership\Members\Contracts\UpdateMemberServiceInterface;
use App\Modules\Membership\Members\DTO\MembersFiltersDTO;
use App\Modules\Membership\Members\Requests\MembersFiltersRequest;
use App\Modules\Membership\Members\Requests\MembersRequest;
use App\Modules\Membership\Members\Requests\MembersUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class MembersController
{
    public function __construct(
        private FindAllMembersServiceInterface $findAllMembersService,
        private ShowByUserIdServiceInterface $showByUserIdService,
        private CreateMemberServiceInterface $createMemberService,
        private UpdateMemberServiceInterface $updateMemberService,
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

        $membersFiltersDTO->churchIds = $membersFiltersRequest->churchIds;
        $membersFiltersDTO->profileId = $membersFiltersRequest->profileId;
        $membersFiltersDTO->name      = $membersFiltersRequest->name;
        $membersFiltersDTO->cityId    = $membersFiltersRequest->cityId;

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
        $userDTO->active    = $membersRequest->active;

        $userDTO->member->churchId      = $membersRequest->churchId;
        $userDTO->person->phone         = $membersRequest->phone;
        $userDTO->person->zipCode       = $membersRequest->zipCode;
        $userDTO->person->address       = $membersRequest->address;
        $userDTO->person->numberAddress = $membersRequest->numberAddress;
        $userDTO->person->complement    = $membersRequest->complement;
        $userDTO->person->district      = $membersRequest->district;
        $userDTO->person->cityId        = $membersRequest->cityId;
        $userDTO->person->uf            = $membersRequest->uf;

        $created = $this->createMemberService->execute($userDTO);

        return response()->json($created, Response::HTTP_OK);
    }

    public function update(
        MembersUpdateRequest $membersUpdateRequest,
        UserDTO $userDTO
    ): JsonResponse
    {
        $userDTO->id    = $membersUpdateRequest->id;
        $userDTO->name  = $membersUpdateRequest->name;
        $userDTO->email = $membersUpdateRequest->email;

        $userDTO->person->phone         = $membersUpdateRequest->phone;
        $userDTO->person->zipCode       = $membersUpdateRequest->zipCode;
        $userDTO->person->address       = $membersUpdateRequest->address;
        $userDTO->person->numberAddress = $membersUpdateRequest->numberAddress;
        $userDTO->person->complement    = $membersUpdateRequest->complement;
        $userDTO->person->district      = $membersUpdateRequest->district;
        $userDTO->person->cityId        = $membersUpdateRequest->cityId;
        $userDTO->person->uf            = $membersUpdateRequest->uf;

        $updated = $this->updateMemberService->execute($userDTO);

        return response()->json($updated, Response::HTTP_OK);
    }
}
