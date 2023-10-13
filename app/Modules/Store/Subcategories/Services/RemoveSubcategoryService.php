<?php

namespace App\Modules\Store\Subcategories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Subcategories\Contracts\RemoveSubcategoryServiceInterface;
use App\Modules\Store\Subcategories\Contracts\SubcategoriesRepositoryInterface;
use App\Modules\Store\Subcategories\Validations\SubcategoriesValidations;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveSubcategoryService extends AuthenticatedService implements RemoveSubcategoryServiceInterface
{
    public function __construct(
        private readonly SubcategoriesRepositoryInterface $subcategoriesRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_SUBCATEGORIES_DELETE->value);

        SubcategoriesValidations::subcategoryExists(
            $id,
            $this->subcategoriesRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->subcategoriesRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
