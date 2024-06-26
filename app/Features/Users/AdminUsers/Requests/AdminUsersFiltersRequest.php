<?php

namespace App\Features\Users\AdminUsers\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

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
            'profileId' => ['nullable', 'string', new Uuid4Rule],
            'email'     => 'nullable|email',
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
