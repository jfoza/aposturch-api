<?php

namespace Database\Factories\Modules\Store\Departments\Models;

use App\Modules\Store\Departments\Models\Department;
use App\Shared\Helpers\RandomStringHelper;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        return [
            Department::NAME => RandomStringHelper::alnumGenerate(),
            Department::DESCRIPTION => RandomStringHelper::alnumGenerate(),
        ];
    }
}
