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

        return [
            Product::PRODUCT_NAME => $name,
            Product::PRODUCT_DESCRIPTION => RandomStringHelper::alnumGenerate(),
            Product::PRODUCT_UNIQUE_NAME => $uniqueName,
            Product::VALUE => 0.00,
            Product::QUANTITY => 0,
            Product::BALANCE => 0,
            Product::HIGHLIGHT_PRODUCT => false,
        ];
    }
}
