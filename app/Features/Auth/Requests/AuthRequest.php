<?php

namespace App\Features\Auth\Requests;

use App\Base\Http\Requests\FormRequest;

class AuthRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'email'    => 'E-mail',
            'password' => 'Password',
        ];
    }
}

