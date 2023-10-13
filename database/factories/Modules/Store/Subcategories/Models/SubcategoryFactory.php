<?php

namespace Database\Factories\Modules\Store\Subcategories\Models;

use App\Modules\Store\Categories\Models\Category;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        $category = Category::factory()->create();

        return [
            Subcategory::CATEGORY_ID => $category->id,
            Subcategory::NAME => RandomStringHelper::alnumGenerate(),
            Subcategory::DESCRIPTION => RandomStringHelper::alnumGenerate(),
        ];
    }
}
