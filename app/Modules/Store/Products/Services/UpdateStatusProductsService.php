<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Exceptions\EnvironmentException;
use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Contracts\UpdateStatusProductsServiceInterface;
use App\Modules\Store\Products\Validations\ProductsValidators;
use App\Shared\Enums\RulesEnum;
use App\Shared\Utils\Transaction;
use Illuminate\Support\Collection;

class UpdateStatusProductsService extends AuthenticatedService implements UpdateStatusProductsServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository,
        private readonly ProductsPersistenceRepositoryInterface $productsPersistenceRepository,
    ) {}

    /**
     * @throws AppException
     */
    public function execute(array $productsId): Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_STATUS_UPDATE->value);

        $products = ProductsValidators::productsExists(
            $productsId,
            $this->productsRepository
        );

        Transaction::beginTransaction();

        try
        {
            $products = $products->map(
                fn($item) => $this->productsPersistenceRepository->saveStatus($item->id, !$item->active)
            );

            Transaction::commit();

            return $products;
        }
        catch (\Exception $e)
        {
            Transaction::rollback();

            EnvironmentException::dispatchException($e);
        }
    }
}
