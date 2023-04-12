<?php

namespace App\Modules\Members\Church\Traits;

use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Modules\Members\Church\Contracts\ChurchRepositoryInterface;
use Illuminate\Support\Facades\Storage;

trait ChurchOperationsTrait
{
    public function removeImageIfAlreadyExists(
        object $church,
        ChurchRepositoryInterface $churchRepository,
        ImagesRepositoryInterface $imagesRepository,
    ): void
    {
        if(count($church->imagesChurch) > 0) {
            $images = $church->imagesChurch;

            $churchRepository->saveImages($church->id, []);

            foreach ($images as $image)
            {
                $imagesRepository->remove($image->id);

                Storage::delete($image->path);
            }
        }
    }
}
