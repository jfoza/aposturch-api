<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidations;
use App\Modules\Store\Subcategories\Contracts\CreateSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\DTO\SubcategoriesDTO;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class CreateSubcategoryService extends AuthenticatedService implements CreateSubcategoryServiceInterface
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
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_INSERT->value);

        CategoriesValidations::categoryExists(
            $subcategoriesDTO->categoryId,
            $this->categoriesRepository
        );

        SubcategoriesValidations::subcategoryExistsByName(
            $subcategoriesDTO->name,
            $this->subcategoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $created = $this->subcategoriesRepository->create($subcategoriesDTO);

            Transaction::commit();

            return $created;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
