<?php

namespace App\Modules\Store\Departments\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Departments\Models\Department;

class DepartmentsFiltersRequest extends FormRequest
{
    public array $sorting = [
        Department::NAME,
        Department::CREATED_AT,
    ];

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name' => 'nullable|string',
            'active' => 'nullable|boolean',
            'hasCategories' => 'nullable|boolean',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name' => 'Name',
            'active' => 'Active',
            'hasCategories' => 'Has Categories'
        ]);
    }

    public function authorize(): true
    {
        return true;
    }
}
