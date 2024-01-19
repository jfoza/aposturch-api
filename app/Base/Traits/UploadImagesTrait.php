<?php

namespace App\Base\Traits;

use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\Users\Users\Contracts\UsersRepositoryInterface;
use App\Modules\Membership\Church\Contracts\ChurchRepositoryInterface;
use App\Modules\Store\Products\Repositories\ProductsPersistenceRepository;
use Illuminate\Support\Facades\Storage;

trait UploadImagesTrait
{
    public function removeImages(
        mixed $images,
        ImagesRepositoryInterface $imagesRepository,
    ): void
    {
        foreach ($images as $image)
        {
            $imagesRepository->remove($image->id);

            Storage::delete($image->path);
        }
    }

    public function removeChurchImageIfAlreadyExists(
        object $church,
        ChurchRepositoryInterface $churchRepository,
        ImagesRepositoryInterface $imagesRepository,
    ): void
    {
        if(count($church->imagesChurch) > 0) {
            $images = $church->imagesChurch;

            $churchRepository->saveImages($church->id, []);

            $this->removeImages(
                $images,
                $imagesRepository
            );
        }
    }

    public function removeProductImageIfAlreadyExists(
        object $product,
        ProductsPersistenceRepository $productsPersistenceRepository,
        ImagesRepositoryInterface $imagesRepository,
    ): void
    {
        if(count($product->image) > 0) {
            $images = $product->image;

            $productsPersistenceRepository->saveImages($product->id, []);

            $this->removeImages(
                $images,
                $imagesRepository
            );
        }
    }

    public function removeUserMemberImageIfAlreadyExists(
        object $user,
        UsersRepositoryInterface $usersRepository,
        ImagesRepositoryInterface $imagesRepository,
    ): void
    {
        if(!empty($user->avatar_id)) {
            $usersRepository->saveAvatar($user->id, null);

            $imagesRepository->remove($user->avatar_id);

            $path = !empty($user->image->path) ? $user->image->path : null;

            if(!is_null($path) && Storage::exists($path)) {
                Storage::delete($path);
            }
        }
    }
}
