<?php

namespace App\Modules\Store\Subcategories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class SubcategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoryId'  => ['nullable', 'string', new Uuid4Rule()],
            'name'        => 'required|string',
            'description' => 'nullable|string',
            'productsId'  => 'nullable|array',

            'productsId.*' => ['nullable', new Uuid4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'categoryId'  => 'Category Id',
            'name'        => 'Name',
            'description' => 'Description',
            'productsId'  => 'Products Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
