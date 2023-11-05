<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class CategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'departmentId' => ['required', 'string', new Uuid4Rule()],
            'name'         => 'required|string',
            'description'  => 'nullable|string',
            'productsId'   => 'nullable|array',

            'productsId.*' => ['nullable', new Uuid4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'departmentId' => 'Department Id',
            'name'         => 'Name',
            'description'  => 'Description',
            'productsId'   => 'Products Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
