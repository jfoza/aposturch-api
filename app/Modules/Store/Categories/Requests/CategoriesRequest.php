<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class CategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'            => 'required|string',
            'description'     => 'nullable|string',
            'subcategoriesId' => 'nullable|array',

            'subcategoriesId.*' => ['nullable', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'            => 'Name',
            'description'     => 'Description',
            'subcategoriesId' => 'Subcategories Id'
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
