<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\CreateProductServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Helpers\Helpers;
use App\Shared\Utils\Transaction;

class CreateProductService extends AuthenticatedService implements CreateProductServiceInterface
{
    public function __construct(
        private readonly ProductsPersistenceRepositoryInterface $productsPersistenceRepository,
        private readonly ProductsRepositoryInterface            $productsRepository,
        private readonly SubcategoriesRepositoryInterface       $subcategoriesRepository,
    ) {}

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

        if(count($productsDTO->subcategoriesId) > 0)
        {
            SubcategoriesValidators::subcategoriesExists(
                $productsDTO->subcategoriesId,
                $this->subcategoriesRepository
            );
        }

        Transaction::beginTransaction();

        try
        {
            $productsDTO->productUniqueName = Helpers::stringUniqueName($productsDTO->productName);

            $productsDTO->productCode = strtoupper($productsDTO->productCode);

            $product = $this->productsPersistenceRepository->create($productsDTO);

            $this
                ->productsPersistenceRepository
                ->saveSubcategories(
                    $product->id,
                    $productsDTO->subcategoriesId
                );

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
