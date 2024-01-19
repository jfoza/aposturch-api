<?php

namespace App\Modules\Store\Departments\Requests;

use App\Base\Http\Requests\FormRequest;

class DepartmentsRequest extends FormRequest
{
    public function rules(): array
    {
        $namesRules = ['required', 'string'];

        return [
            'name'        => $namesRules,
            'description' => 'nullable|string'
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'Name',
            'description' => 'Description',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
