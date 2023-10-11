<?php

namespace App\Features\Users\Users\Requests;

use App\Base\Http\Requests\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'currentPassword' => 'required|string',
            'newPassword'     => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'currentPassword' => 'Senha atual',
            'newPassword'     => 'Nova senha',
        ];
    }
}
