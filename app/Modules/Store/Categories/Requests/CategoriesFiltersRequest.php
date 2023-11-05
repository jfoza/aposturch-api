<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class CategoriesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name'         => 'nullable|string',
            'departmentId' => ['nullable', 'string', new Uuid4Rule()],
            'active'       => 'nullable|boolean',
            'hasProducts'  => 'nullable|boolean',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'         => 'Name',
            'departmentId' => 'Department Id',
            'active'       => 'Active',
            'hasProducts'  => 'Has Products',
        ]);
    }

    public function authorize(): true
    {
        return true;
    }
}
