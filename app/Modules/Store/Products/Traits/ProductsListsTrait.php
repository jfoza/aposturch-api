<?php

namespace App\Modules\Store\Products\Traits;

use App\Base\Http\Pagination\PaginationOrder;
use App\Features\General\Images\Enums\TypeOriginImageEnum;
use App\Features\General\Images\Models\Image;
use App\Modules\Store\Products\DTO\ProductsFiltersDTO;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Helpers\Helpers;
use Illuminate\Contracts\Database\Query\Expression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ProductsListsTrait
{
    public function getBaseQuery(): Builder
    {
        return Product::with([
            'category',
            'image' => function($q) {
                return $q
                    ->select(
                        Image::tableField(Image::ID),
                        Image::tableField(Image::TYPE),
                        Image::tableField(Image::ORIGIN),
                        $this->generateImagePath()
                    );
            }
        ])
            ->select(
                Product::tableField(Product::ID),
                Product::tableField(Product::PRODUCT_NAME),
                Product::tableField(Product::PRODUCT_DESCRIPTION),
                Product::tableField(Product::PRODUCT_UNIQUE_NAME),
                Product::tableField(Product::PRODUCT_CODE),
                Product::tableField(Product::PRODUCT_VALUE),
                Product::tableField(Product::PRODUCT_QUANTITY),
                Product::tableField(Product::PRODUCT_BALANCE),
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
                isset($productsFiltersDTO->nameOrCode),
                fn($q) => $q
                    ->where(
                        fn($aux) => $aux
                            ->where(
                                Product::tableField(Product::PRODUCT_NAME),
                                'ilike',
                                "$productsFiltersDTO->nameOrCode%"
                            )
                            ->orWhere(
                                Product::tableField(Product::PRODUCT_CODE),
                                'ilike',
                                "$productsFiltersDTO->nameOrCode%"
                            )
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

    public function getColumnName(PaginationOrder $paginationOrder): string
    {
        return match ($paginationOrder->getColumnName())
        {
            Product::PRODUCT_NAME      => Product::tableField(Product::PRODUCT_NAME),
            Product::PRODUCT_VALUE     => Product::tableField(Product::PRODUCT_VALUE),
            Product::PRODUCT_BALANCE   => Product::tableField(Product::PRODUCT_BALANCE),
            Product::HIGHLIGHT_PRODUCT => Product::tableField(Product::HIGHLIGHT_PRODUCT),
            Product::ACTIVE            => Product::tableField(Product::ACTIVE),

            default => Product::tableField(Product::CREATED_AT)
        };
    }

    public function generateImagePath(): Expression
    {
        $imageUploadLink = Helpers::getApiUrl("storage/");

        $origin     = Image::tableField(Image::ORIGIN);
        $path       = Image::tableField(Image::PATH);
        $uploadType = TypeOriginImageEnum::UPLOAD->value;

        return DB::raw("
            CASE
                WHEN $origin = '$uploadType' THEN CONCAT('$imageUploadLink', $path)
                ELSE $path
            END
        ");
    }
}
