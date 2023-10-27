<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Departments\Contracts\DepartmentsRepositoryInterface;
use App\Modules\Store\Departments\Validations\DepartmentsValidations;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateSubcategoryService extends AuthenticatedService implements UpdateSubcategoryServiceInterface
{
    private SubcategoriesDTO $subcategoriesDTO;

    public function __construct(
        private readonly DepartmentsRepositoryInterface   $categoriesRepository,
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
        private readonly ProductsRepositoryInterface      $productsRepository,
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

            $this->subcategoriesRepository->saveProducts($updated->id, $this->subcategoriesDTO->productsId);

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
        SubcategoriesValidators::subcategoryExists(
            $this->subcategoriesDTO->id,
            $this->subcategoriesRepository
        );

        SubcategoriesValidators::subcategoryExistsByNameInUpdate(
            $this->subcategoriesDTO->id,
            $this->subcategoriesDTO->name,
            $this->subcategoriesRepository
        );

        DepartmentsValidations::departmentExists(
            $this->subcategoriesDTO->departmentId,
            $this->categoriesRepository
        );

        if(count($this->subcategoriesDTO->productsId) > 0)
        {
            ProductsValidators::productsExists(
                $this->subcategoriesDTO->productsId,
                $this->productsRepository
            );
        }
    }
}
