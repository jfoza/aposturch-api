<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Validations\ProductsValidations;
use App\Modules\Store\Subcategories\Contracts\CreateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateSubcategoryService extends AuthenticatedService implements CreateSubcategoryServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_INSERT->value);

        $this->subcategoriesDTO = $subcategoriesDTO;

        $this->handleValidations();

        Transaction::beginTransaction();

        try
        {
            $created = $this->subcategoriesRepository->create($this->subcategoriesDTO);

            if($this->hasProducts)
            {
                $this->subcategoriesRepository->saveProducts($created->id, $this->subcategoriesDTO->productsId);
            }

            Transaction::commit();

            return $created;
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

        CategoriesValidations::categoryExists(
            $this->subcategoriesDTO->categoryId,
            $this->categoriesRepository
        );

        SubcategoriesValidations::subcategoryExistsByName(
            $this->subcategoriesDTO->name,
            $this->subcategoriesRepository
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
