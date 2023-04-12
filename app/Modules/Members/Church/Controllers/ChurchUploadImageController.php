<?php

namespace App\Modules\Members\Church\Controllers;

use App\Features\General\Images\DTO\ImagesDTO;
use App\Modules\Members\Church\Contracts\ChurchUploadImageServiceInterface;
use App\Modules\Members\Church\Requests\ChurchUploadImageRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

readonly class ChurchUploadImageController
{
    public function __construct(
        private ChurchUploadImageServiceInterface $churchUploadImageService
    ) {}

    public function store(
        ImagesDTO $imagesDTO,
        ChurchUploadImageRequest $churchUploadImageRequest
    ): JsonResponse
    {
        $imagesDTO->image = $churchUploadImageRequest->image;
        $churchId = $churchUploadImageRequest->churchId;

        $image = $this->churchUploadImageService->execute(
            $imagesDTO,
            $churchId
        );

        return response()->json($image, Response::HTTP_OK);
    }
}
