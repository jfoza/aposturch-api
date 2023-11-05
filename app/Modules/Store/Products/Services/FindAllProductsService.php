<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Exceptions\AppException;
use App\Modules\Store\Products\Contracts\FindAllProductsServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Shared\Enums\RulesEnum;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllProductsService extends AuthenticatedService implements FindAllProductsServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository
    ) {}

    /**
     * @throws AppException
     */
    public function execute(ProductsFiltersDTO $productsFiltersDTO): LengthAwarePaginator|Collection
    {
        $this->getPolicy()->havePermission(RulesEnum::STORE_MODULE_PRODUCTS_VIEW->value);

        return $this->productsRepository->findAll($productsFiltersDTO);
    }
}
