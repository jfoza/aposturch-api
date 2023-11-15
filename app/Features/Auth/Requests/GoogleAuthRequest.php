<?php

namespace App\Features\Auth\Requests;

use App\Base\Http\Requests\FormRequest;

class GoogleAuthRequest extends FormRequest
{
    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'googleAuthToken' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'googleAuthToken' => 'Google Auth Token',
        ];
    }
}

