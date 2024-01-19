<?php

namespace App\Features\Users\ForgotPassword\Requests;

use App\Base\Http\Requests\FormRequest;

class EmailRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function attributes(): array
    {
        return [
            'email' => 'E-mail',
        ];
    }
}

