<?php

namespace App\Modules\Store\Products\Services;

use App\Base\Services\AuthenticatedService;
use App\Modules\Store\Products\Contracts\FindAllProductsServiceInterface;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class FindAllProductsService extends AuthenticatedService implements FindAllProductsServiceInterface
{
    public function __construct(
        private readonly ProductsRepositoryInterface $productsRepository
    ) {}

    public function execute(ProductsFiltersDTO $productsFiltersDTO): LengthAwarePaginator|Collection
    {
        return $this->productsRepository->findAll($productsFiltersDTO);
    }
}
