<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Categories\Models\Category;

class CategoriesFiltersRequest extends FormRequest
{
    public array $sorting = [
        Category::NAME,
        Category::CREATED_AT,
    ];

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name' => 'nullable|string',
            'active' => 'nullable|boolean',
            'hasSubcategories' => 'nullable|boolean',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name' => 'Name',
            'active' => 'Active',
            'hasSubcategories' => 'Has Subcategories'
        ]);
    }

    public function authorize(): true
    {
        return true;
    }
}
