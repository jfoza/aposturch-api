<?php

namespace Database\Factories\Modules\Store\Products\Models;

use App\Modules\Store\Products\Models\Product;
use App\Shared\Helpers\Helpers;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = RandomStringHelper::alnumGenerate();

        $uniqueName = Helpers::stringUniqueName($name);

        $productCode = strtoupper(RandomStringHelper::alphaGenerate(2).RandomStringHelper::numericGenerate(5));

        return [
            Product::PRODUCT_NAME => $name,
            Product::PRODUCT_DESCRIPTION => RandomStringHelper::alnumGenerate(),
            Product::PRODUCT_UNIQUE_NAME => $uniqueName,
            Product::PRODUCT_CODE => $productCode,
            Product::PRODUCT_VALUE => 0.00,
            Product::PRODUCT_QUANTITY => 0,
            Product::PRODUCT_BALANCE => 0,
            Product::HIGHLIGHT_PRODUCT => false,
        ];
    }
}
