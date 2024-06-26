<?php

namespace App\Features\Users\ForgotPassword\Requests;

use App\Base\Http\Requests\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'password' => 'required|string',
            'passwordConfirmation' => 'required|same:password'
        ];
    }

    public function attributes(): array
    {
        return [
            'password' => 'Senha',
            'passwordConfirmation' => 'Confirmação de senha',
        ];
    }
}

