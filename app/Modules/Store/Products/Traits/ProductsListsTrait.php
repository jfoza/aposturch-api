<?php

namespace App\Modules\Store\Products\Traits;

use App\Base\Http\Pagination\PaginationOrder;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use Illuminate\Database\Eloquent\Builder;

trait ProductsListsTrait
{
    public function getBaseQuery(): Builder
    {
        return Product::with(['category'])
            ->select(
                Product::tableField(Product::ID),
                Product::tableField(Product::PRODUCT_NAME),
                Product::tableField(Product::PRODUCT_DESCRIPTION),
                Product::tableField(Product::PRODUCT_UNIQUE_NAME),
                Product::tableField(Product::PRODUCT_CODE),
                Product::tableField(Product::VALUE),
                Product::tableField(Product::QUANTITY),
                Product::tableField(Product::BALANCE),
                Product::tableField(Product::HIGHLIGHT_PRODUCT),
                Product::tableField(Product::ACTIVE),
                Product::tableField(Product::CREATED_AT),
            );
    }

    public function getBaseQueryFiltered(ProductsFiltersDTO $productsFiltersDTO)
    {
        return $this
            ->getBaseQuery()
            ->when(
                isset($productsFiltersDTO->name),
                fn($q) => $q->where(
                    Product::tableField(Product::PRODUCT_NAME),
                    'ilike',
                    "%$productsFiltersDTO->name%"
                )
            )
            ->when(
                isset($productsFiltersDTO->categoriesId),
                fn($q) => $q->whereHas(
                    'category',
                    fn($s) => $s->whereIn(
                        Category::tableField(Category::ID),
                        $productsFiltersDTO->categoriesId
                    )
                )
            )
            ->when(
                isset($productsFiltersDTO->code),
                fn($q) => $q->where(
                    Product::tableField(Product::PRODUCT_CODE),
                    'ilike',
                    "%$productsFiltersDTO->code%"
                )
            )
            ->when(
                isset($productsFiltersDTO->highlight),
                fn($q) => $q->where(
                    Product::tableField(Product::HIGHLIGHT_PRODUCT),
                    $productsFiltersDTO->highlight
                )
            )
            ->when(
                isset($productsFiltersDTO->active),
                fn($q) => $q->where(
                    Product::tableField(Product::ACTIVE),
                    $productsFiltersDTO->active
                )
            );
    }

    private function getColumnName(PaginationOrder $paginationOrder): string
    {
        return match ($paginationOrder->getColumnName())
        {
            Product::PRODUCT_NAME      => Product::tableField(Product::PRODUCT_NAME),
            Product::VALUE             => Product::tableField(Product::VALUE),
            Product::BALANCE           => Product::tableField(Product::BALANCE),
            Product::HIGHLIGHT_PRODUCT => Product::tableField(Product::HIGHLIGHT_PRODUCT),
            Product::ACTIVE            => Product::tableField(Product::ACTIVE),

            default => Product::tableField(Product::CREATED_AT)
        };
    }
}
