<?php

namespace App\Features\Users\Users\Requests;

use App\Features\Base\Http\Requests\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string',
            'email'                => 'required|email:rfc,dns',
            'password'             => 'nullable|string',
            'passwordConfirmation' => 'nullable|same:password',
            'active'               => 'required|bool',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'E-mail',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password Confirmation',
            'active'               => 'Active',
        ];
    }
}
