<?php

namespace App\Features\Users\Users\Requests;

use App\Features\Base\Http\Requests\FormRequest;

class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword'      => 'required|string',
            'newPassword'          => 'required|string',
            'passwordConfirmation' => 'required|same:newPassword',
        ];
    }

    public function attributes(): array
    {
        return [
            'currentPassword'      => 'Current Password',
            'newPassword'          => 'New Password',
            'passwordConfirmation' => 'Password confirmation',
        ];
    }
}

