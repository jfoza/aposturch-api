<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Features\Base\Http\Requests\FormRequest;

class PasswordDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = 'required|string';

        return [
            'password'             => $requiredString,
            'passwordConfirmation' => 'required|same:password',
        ];
    }

    public function authorize(): array
    {
        return [
            'password'             => 'Password',
            'passwordConfirmation' => 'Password confirmation',
        ];
    }
}
