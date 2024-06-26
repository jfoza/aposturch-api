<?php

namespace App\Features\General\Images\Repositories;

use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\DTO\ImagesDTO;
use App\Features\General\Images\Models\Image;

class ImagesRepository implements ImagesRepositoryInterface
{
    public function findById(string $id)
    {
        return Image::where(Image::ID, $id)->first();
    }

    public function create(ImagesDTO $imagesDTO): object
    {
        return Image::create([
            Image::PATH   => $imagesDTO->path,
            Image::TYPE   => $imagesDTO->type,
            Image::ORIGIN => $imagesDTO->origin,
        ]);
    }

    public function save(ImagesDTO $imagesDTO)
    {
        Image::where(Image::ID, $imagesDTO->id)
            ->update([
                Image::PATH   => $imagesDTO->path,
            ]);
    }

    public function remove(string $id): void
    {
        Image::where(Image::ID, $id)->delete();
    }
}
