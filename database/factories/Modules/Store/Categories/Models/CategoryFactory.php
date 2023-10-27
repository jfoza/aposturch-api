<?php

namespace Database\Factories\Modules\Store\Categories\Models;

use App\Modules\Store\Departments\Models\Department;
use App\Modules\Store\Categories\Models\Category;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $department = Department::factory()->create();

        return [
            Category::DEPARTMENT_ID => $department->id,
            Category::NAME => RandomStringHelper::alnumGenerate(),
            Category::DESCRIPTION => RandomStringHelper::alnumGenerate(),
        ];
    }
}
