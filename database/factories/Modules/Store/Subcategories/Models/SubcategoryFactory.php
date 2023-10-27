<?php

namespace Database\Factories\Modules\Store\Subcategories\Models;

use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Subcategories\Models\Subcategory;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubcategoryFactory extends Factory
{
    protected $model = Subcategory::class;

    public function definition(): array
    {
        $department = Department::factory()->create();

        return [
            Subcategory::DEPARTMENT_ID => $department->id,
            Subcategory::NAME => RandomStringHelper::alnumGenerate(),
            Subcategory::DESCRIPTION => RandomStringHelper::alnumGenerate(),
        ];
    }
}
