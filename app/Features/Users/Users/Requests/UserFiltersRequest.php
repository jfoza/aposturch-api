<?php

namespace App\Features\Users\Users\Requests;

use App\Base\Http\Requests\FormRequest;

class UserFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->mergePaginationOrderRules(
            [
                'name' => 'nullable|string',
            ]
        );
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes(
            [
                'name' => 'Name',
            ]
        );
    }
}

