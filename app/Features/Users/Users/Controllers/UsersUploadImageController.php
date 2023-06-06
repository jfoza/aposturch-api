<?php

namespace App\Features\Users\Users\Controllers;

use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\Users\Users\Contracts\UserUploadImageServiceInterface;
use App\Features\Users\Users\Requests\UsersUploadImageRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class UsersUploadImageController
{
    public function __construct(
        private UserUploadImageServiceInterface $userUploadImageService,
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
}
