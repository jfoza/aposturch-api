<?php

namespace App\Modules\Store\Products\Repositories;

use App\Modules\Store\Products\Contracts\ProductsPersistenceRepositoryInterface;
use App\Modules\Store\Products\DTO\ProductsDTO;
use App\Modules\Store\Products\Models\Product;

class ProductsPersistenceRepository implements ProductsPersistenceRepositoryInterface
{
    public function create(ProductsDTO $productsDTO): object
    {
        return Product::create([
            Product::PRODUCT_NAME        => $productsDTO->productName,
            Product::PRODUCT_DESCRIPTION => $productsDTO->productDescription,
            Product::PRODUCT_UNIQUE_NAME => $productsDTO->productUniqueName,
            Product::PRODUCT_CODE        => $productsDTO->productCode,
            Product::VALUE               => $productsDTO->value,
            Product::QUANTITY            => $productsDTO->quantity,
            Product::BALANCE             => $productsDTO->balance,
            Product::HIGHLIGHT_PRODUCT   => $productsDTO->highlightProduct,
        ]);
    }

    public function save(ProductsDTO $productsDTO): object
    {
        $update = [
            Product::ID                  => $productsDTO->id,
            Product::PRODUCT_NAME        => $productsDTO->productName,
            Product::PRODUCT_DESCRIPTION => $productsDTO->productDescription,
            Product::PRODUCT_UNIQUE_NAME => $productsDTO->productUniqueName,
            Product::PRODUCT_CODE        => $productsDTO->productCode,
            Product::VALUE               => $productsDTO->value,
            Product::QUANTITY            => $productsDTO->quantity,
            Product::BALANCE             => $productsDTO->balance,
            Product::HIGHLIGHT_PRODUCT   => $productsDTO->highlightProduct,
        ];

        Product::find($productsDTO->id)->update($update);

        return (object) ($update);
    }

    public function saveSubcategories(string $productId, array $subcategoriesId): void
    {
        Product::find($productId)->subcategory()->sync($subcategoriesId);
    }

    public function saveStatus(string $id, bool $status): object
    {
        Product::where(Product::ID, $id)->update([Product::ACTIVE => $status]);

        return (object) ([
            Product::ID     => $id,
            Product::ACTIVE => $status,
        ]);
    }
}
