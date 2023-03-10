<?php

namespace App\Features\Users\Users\Http\Requests;

use App\Shared\Rules\Uuidv4Rule;
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
            'name'      => 'required|string',
            'email'     => 'required|email:rfc,dns',
            'active'    => 'required|bool',
            'profileId' => ['required', 'string', new Uuidv4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'      => 'Name',
            'email'     => 'E-mail',
            'active'    => 'Active',
            'profileId' => 'Profile',
        ];
    }
}
