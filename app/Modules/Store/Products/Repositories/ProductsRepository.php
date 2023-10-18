<?php

namespace App\Modules\Store\Products\Repositories;

use App\Modules\Store\Products\Contracts\ProductsRepositoryInterface;
use App\Modules\Store\Products\Models\Product;
use App\Modules\Store\Subcategories\Models\Subcategory;
use Illuminate\Support\Collection;

class ProductsRepository implements ProductsRepositoryInterface
{
    public function findAllByIds(array $productsId): Collection
    {
        $products = Product::whereIn(Product::ID, $productsId)->get();

        return collect($products);
    }

    public function findBySubcategory(string $subcategoryId): Collection
    {
        $products = Product::whereRelation(
                'subcategory',
                Subcategory::tableField(Subcategory::ID),
                $subcategoryId
            )
            ->get();

        return collect($products);
    }
}
