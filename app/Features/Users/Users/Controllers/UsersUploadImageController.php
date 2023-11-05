<?php

namespace App\Features\Users\Users\Controllers;

use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\Users\Users\Contracts\RemoveUserAvatarServiceInterface;
use App\Features\Users\Users\Contracts\UserUploadImageServiceInterface;
use App\Features\Users\Users\Requests\UsersUploadImageRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersUploadImageController
{
    public function __construct(
        private UserUploadImageServiceInterface $userUploadImageService,
        private RemoveUserAvatarServiceInterface $removeUserAvatarService,
    ) {}

    public function store(
        ImagesDTO $imagesDTO,
        UsersUploadImageRequest $usersUploadImageRequest
    ): JsonResponse
    {
        $imagesDTO->image = $usersUploadImageRequest->image;
        $userId = $usersUploadImageRequest->userId;

        $uploaded = $this->userUploadImageService->execute(
            $imagesDTO,
            $userId
        );

        return response()->json($uploaded, Response::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->id;

        $this->removeUserAvatarService->execute($id);

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
