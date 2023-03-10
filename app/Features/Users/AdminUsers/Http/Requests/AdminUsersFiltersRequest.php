<?php

namespace App\Features\Users\AdminUsers\Http\Requests;

use App\Features\Base\Http\Requests\FormRequest;

class AdminUsersFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nullableString = 'nullable|string';

        return $this->mergePaginationOrderRules([
            'personName'  => $nullableString,
            'userEmail'   => 'nullable|email:rfc,dns',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'personName'  => 'Person Name',
            'userEmail'   => 'Email',
        ]);
    }
}
