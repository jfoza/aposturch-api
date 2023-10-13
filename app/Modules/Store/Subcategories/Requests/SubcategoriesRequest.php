<?php

namespace App\Modules\Store\Subcategories\Requests;

use App\Base\Http\Requests\FormRequest;

class SubcategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoryId'  => 'required|string',
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'categoryId'  => 'Category Id',
            'name'        => 'Name',
            'description' => 'Description'
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
