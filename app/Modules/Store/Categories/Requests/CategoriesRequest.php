<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;

class CategoriesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'        => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Name',
            'description' => 'Description'
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
