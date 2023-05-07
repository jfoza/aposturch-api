<?php

namespace App\Modules\Membership\Members\Controllers;

use App\Modules\Membership\Members\Contracts\FindAllMembersResponsibleServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class MembersController
{
    public function __construct(
        private FindAllMembersResponsibleServiceInterface $findAllMembersResponsibleService,
    ) {}

    public function getMembersResponsible(): JsonResponse
    {
        $members = $this->findAllMembersResponsibleService->execute();

        return response()->json($members, Response::HTTP_OK);
    }
}
