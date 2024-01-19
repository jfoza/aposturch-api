<?php

namespace App\Features\Users\Users\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\NoSpecialCharactersRule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', new NoSpecialCharactersRule],
            'email'                => 'required|email',
            'password'             => 'nullable|string',
            'passwordConfirmation' => 'nullable|same:password',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'E-mail',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password Confirmation',
        ];
    }
}
