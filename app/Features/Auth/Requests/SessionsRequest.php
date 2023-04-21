<?php

namespace App\Features\Auth\Requests;

use App\Features\Base\Http\Requests\FormRequest;

class SessionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email'    => 'required|email:rfc,dns',
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

