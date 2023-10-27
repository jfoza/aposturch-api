<?php

namespace App\Modules\Store\Products\Repositories;

use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Traits\ProductsListsTrait;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductsRepository implements ProductsRepositoryInterface
{
    use BuilderTrait;
    use ProductsListsTrait;

    public function findAll(ProductsFiltersDTO $productsFiltersDTO): LengthAwarePaginator|Collection
    {
        $builder = $this
            ->getBaseQueryFiltered($productsFiltersDTO)
            ->orderBy(
                $this->getColumnName($productsFiltersDTO->paginationOrder),
                $productsFiltersDTO->paginationOrder->getColumnOrder(),
            );

        return $this->paginate(
            $builder,
            $productsFiltersDTO->paginationOrder
        );
    }

    public function findById(string $id): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Product::tableField(Product::ID), $id)
            ->first();
    }

    public function findByName(string $productName): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Product::tableField(Product::PRODUCT_NAME), $productName)
            ->first();
    }

    public function findByUniqueName(string $productUniqueName): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Product::tableField(Product::PRODUCT_UNIQUE_NAME), $productUniqueName)
            ->first();
    }

    public function findByCode(string $code): ?object
    {
        return $this
            ->getBaseQuery()
            ->where(Product::tableField(Product::PRODUCT_CODE), $code)
            ->first();
    }

    public function findAllByIds(array $productsId): Collection
    {
        $products = $this
            ->getBaseQuery()
            ->whereIn(Product::ID, $productsId)
            ->get();

        return collect($products);
    }

    public function findByCategory(string $categoryId): Collection
    {
        $products = $this
            ->getBaseQuery()
            ->whereRelation(
                'category',
                Category::tableField(Category::ID),
                $categoryId
            )
            ->get();

        return collect($products);
    }
}
