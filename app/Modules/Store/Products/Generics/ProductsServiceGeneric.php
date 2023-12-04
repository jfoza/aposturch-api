<?php

namespace App\Modules\Store\Products\Generics;

use App\Base\Services\AuthenticatedService;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Features\General\Images\Enums\TypeOriginImageEnum;
use App\Features\General\Images\Enums\TypeUploadImageEnum;
use App\Features\General\Images\Models\Image;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use Illuminate\Support\Collection;

class ProductsServiceGeneric extends AuthenticatedService
{
    private ImagesRepositoryInterface $imagesRepository;
    private ProductsPersistenceRepositoryInterface $productsPersistenceRepository;

    public function createSaveImageLinks(
        ProductsDTO $productsDTO,
        object $saved
    ): void
    {
        $productsDTO->imagesDTO->type   = TypeUploadImageEnum::PRODUCT->value;
        $productsDTO->imagesDTO->origin = TypeOriginImageEnum::LINK->value;

        $images = Collection::empty();

        foreach ($productsDTO->imageLinks as $imageLink)
        {
            if($imageLink)
            {
                $productsDTO->imagesDTO->path = $imageLink;

                $images->push(
                    $this->getImagesRepository()->create($productsDTO->imagesDTO)
                );
            }
        }

        $this
            ->getProductsPersistenceRepository()
            ->saveImages(
                $saved->id,
                $images->pluck(Image::ID)->toArray()
            );
    }

    public function getImagesRepository(): ImagesRepositoryInterface
    {
        return $this->imagesRepository;
    }

    public function setImagesRepository(ImagesRepositoryInterface $imagesRepository): void
    {
        $this->imagesRepository = $imagesRepository;
    }

    public function getProductsPersistenceRepository(): ProductsPersistenceRepositoryInterface
    {
        return $this->productsPersistenceRepository;
    }

    public function setProductsPersistenceRepository(
        ProductsPersistenceRepositoryInterface $productsPersistenceRepository
    ): void
    {
        $this->productsPersistenceRepository = $productsPersistenceRepository;
    }
}
