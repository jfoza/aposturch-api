<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Modules\Store\Products\Contracts\CreateProductServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Generics\ProductsServiceGeneric;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class CreateProductService extends ProductsServiceGeneric implements CreateProductServiceInterface
{
    public function __construct(
        private readonly ProductsPersistenceRepositoryInterface $productsPersistenceRepository,
        private readonly ProductsRepositoryInterface            $productsRepository,
        private readonly CategoriesRepositoryInterface          $categoriesRepository,
        private readonly ImagesRepositoryInterface              $imagesRepository,
    )
    {
        $this->setImagesRepository($this->imagesRepository);
        $this->setProductsPersistenceRepository($this->productsPersistenceRepository);
    }

    /**
     * @throws AppException
     */
    public function execute(ProductsDTO $productsDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_INSERT->value);

        ProductsValidators::productExistsByName(
            $productsDTO->productName,
            $this->productsRepository
        );

        ProductsValidators::productExistsByCode(
            $productsDTO->productCode,
            $this->productsRepository
        );

        if(count($productsDTO->categoriesId) > 0)
        {
            CategoriesValidators::categoriesExists(
                $productsDTO->categoriesId,
                $this->categoriesRepository
            );
        }

        Transaction::beginTransaction();

        try
        {
            $productsDTO->productUniqueName = Helpers::stringUniqueName($productsDTO->productName);

            $productsDTO->productCode = strtoupper($productsDTO->productCode);

            $productCreated = $this->productsPersistenceRepository->create($productsDTO);

            $this
                ->productsPersistenceRepository
                ->saveCategories(
                    $productCreated->id,
                    $productsDTO->categoriesId
                );

            if(count($productsDTO->imageLinks) > 0)
            {
                $this->createSaveImageLinks($productsDTO, $productCreated);
            }

            Transaction::commit();

            return $productCreated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
