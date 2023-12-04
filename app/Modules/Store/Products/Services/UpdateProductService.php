<?php /** @noinspection DuplicatedCode */

namespace App\Modules\Store\Products\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Exceptions\AppException;
use App\Features\General\Images\Contracts\ImagesRepositoryInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\UpdateProductServiceInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Generics\ProductsServiceGeneric;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class UpdateProductService extends ProductsServiceGeneric implements UpdateProductServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_UPDATE->value);

        ProductsValidators::productExists(
           $productsDTO->id,
           $this->productsRepository
        );

        ProductsValidators::productExistsByNameInUpdate(
            $productsDTO->id,
            $productsDTO->productName,
            $this->productsRepository
        );

        ProductsValidators::productExistsByCodeInUpdate(
            $productsDTO->id,
            $productsDTO->productCode,
            $this->productsRepository
        );

        ProductsValidators::productQuantityBalanceValidation(
            $productsDTO->quantity,
            $productsDTO->balance,
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

            $product = $this->productsPersistenceRepository->save($productsDTO);

            $this
                ->productsPersistenceRepository
                ->saveCategories(
                    $product->id,
                    $productsDTO->categoriesId
                );

            $this->createSaveImageLinks($productsDTO, $product);

            Transaction::commit();

            return $product;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
