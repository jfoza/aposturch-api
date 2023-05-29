<?php

namespace App\Features\Users\CustomerUsers\Http\Requests;

use App\Shared\Rules\Uuid4Rule;
use App\Features\Base\Http\Requests\FormRequest;

class CustomerUsersFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'personName' => 'nullable|string',
            'userEmail'  => 'nullable|email:rfc,dns',
            'personCity' => [new Uuid4Rule],
            'userActive' => 'nullable|boolean',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'personName' => 'Person Name',
            'userEmail'  => 'Email',
            'personCity' => 'Person City',
            'userActive' => 'User Active',
        ]);
    }
}
