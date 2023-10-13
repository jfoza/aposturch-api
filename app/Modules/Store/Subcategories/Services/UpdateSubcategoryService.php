<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Contracts\UpdateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class UpdateSubcategoryService extends AuthenticatedService implements UpdateSubcategoryServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(SubcategoriesDTO $subcategoriesDTO): object
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_UPDATE->value);

        SubcategoriesValidations::subcategoryExists(
            $subcategoriesDTO->id,
            $this->subcategoriesRepository
        );

        SubcategoriesValidations::subcategoryExistsByNameInUpdate(
            $subcategoriesDTO->id,
            $subcategoriesDTO->name,
            $this->subcategoriesRepository
        );

        CategoriesValidations::categoryExists(
            $subcategoriesDTO->categoryId,
            $this->categoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $updated = $this->subcategoriesRepository->save($subcategoriesDTO);

            Transaction::commit();

            return $updated;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
