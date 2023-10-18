<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Validations\ProductsValidations;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateSubcategoryService extends AuthenticatedService implements UpdateSubcategoryServiceInterface
{
    private SubcategoriesDTO $subcategoriesDTO;
    private bool $hasProducts = false;

    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
        private readonly ProductsRepositoryInterface $productsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(SubcategoriesDTO $subcategoriesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value);

        $this->subcategoriesDTO = $subcategoriesDTO;

        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $updated = $this->subcategoriesRepository->save($this->subcategoriesDTO);

            if($this->hasProducts)
            {
                $this->subcategoriesRepository->saveProducts($updated->id, $this->subcategoriesDTO->productsId);
            }

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }

    /**
     * @throws AppException
     */
    private function handleValidations(): void
    {
        $this->hasProducts = isset($this->subcategoriesDTO->productsId) && count($this->subcategoriesDTO->productsId) > 0;

        SubcategoriesValidations::subcategoryExists(
            $this->subcategoriesDTO->id,
            $this->subcategoriesRepository
        );

        SubcategoriesValidations::subcategoryExistsByNameInUpdate(
            $this->subcategoriesDTO->id,
            $this->subcategoriesDTO->name,
            $this->subcategoriesRepository
        );

        CategoriesValidations::categoryExists(
            $this->subcategoriesDTO->categoryId,
            $this->categoriesRepository
        );

        if($this->hasProducts)
        {
            ProductsValidations::productsExists(
                $this->subcategoriesDTO->productsId,
                $this->productsRepository
            );
        }
    }
}
