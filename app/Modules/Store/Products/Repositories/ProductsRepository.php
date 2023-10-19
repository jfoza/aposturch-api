<?php

namespace App\Modules\Store\Products\Repositories;

use App\Base\Traits\BuilderTrait;
use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Products\Traits\ProductsListsTrait;
use App\Modules\Store\Subcategories\Models\Subcategory;
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

    public function findAllByIds(array $productsId): Collection
    {
        $products = $this
            ->getBaseQuery()
            ->whereIn(Product::ID, $productsId)
            ->get();

        return collect($products);
    }

    public function findBySubcategory(string $subcategoryId): Collection
    {
        $products = $this
            ->getBaseQuery()
            ->whereRelation(
                'subcategory',
                Subcategory::tableField(Subcategory::ID),
                $subcategoryId
            )
            ->get();

        return collect($products);
    }
}
