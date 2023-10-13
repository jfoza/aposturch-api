<?php

namespace App\Modules\Store\Subcategories\Requests;

use App\Base\Http\Requests\FormRequest;

class SubcategoriesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name'       => 'nullable|string',
            'categoryId' => 'nullable|string',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'       => 'Name',
            'categoryId' => 'Category Id',
        ]);
    }

    public function authorize(): true
    {
        return true;
    }
}
