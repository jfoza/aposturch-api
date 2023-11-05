<?php

namespace App\Modules\Store\Categories\Services;

use App\Base\Services\AuthenticatedService;
use App\Base\Traits\EnvironmentException;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Categories\Contracts\RemoveCategoryServiceInterface;
use App\Modules\Store\Categories\Contracts\CategoriesRepositoryInterface;
use App\Modules\Store\Categories\Validations\CategoriesValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;

class RemoveCategoryService extends AuthenticatedService implements RemoveCategoryServiceInterface
{
    public function __construct(
        private readonly CategoriesRepositoryInterface $categoriesRepository,
        private readonly ProductsRepositoryInterface   $productsRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(string $id): void
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_CATEGORIES_DELETE->value);

        CategoriesValidators::categoryExists(
            $id,
            $this->categoriesRepository
        );

        CategoriesValidators::hasProducts(
            $id,
            $this->productsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $this->categoriesRepository->remove($id);

            Transaction::commit();
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
