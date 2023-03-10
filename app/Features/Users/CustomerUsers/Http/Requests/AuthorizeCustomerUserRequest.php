<?php

namespace App\Features\Users\CustomerUsers\Http\Requests;

use App\Shared\Rules\CodeVerificationEmailRule;
use App\Features\Base\Http\Requests\FormRequest;

class AuthorizeCustomerUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'  => ['required', new CodeVerificationEmailRule],
        ];
    }

    public function attributes(): array
    {
        return [
            'code' => 'Code',
        ];
    }
}
