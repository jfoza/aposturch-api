<?php

namespace App\Features\Users\AdminUsers\Http\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuidv4Rule;

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
            'name'      => $nullableString,
            'profileId' => ['nullable', 'string', new Uuidv4Rule],
            'email'     => 'nullable|email:rfc,dns',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'      => 'Person Name',
            'profileId' => 'Profile Id',
            'email'     => 'Email',
        ]);
    }
}
