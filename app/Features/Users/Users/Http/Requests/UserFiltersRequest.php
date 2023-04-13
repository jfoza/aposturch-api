<?php

namespace App\Features\Users\Users\Http\Requests;

use App\Shared\Rules\Uuidv4Rule;
use App\Features\Base\Http\Requests\FormRequest;

class UserFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'nullable|string',
            'churchId' => ['nullable', 'string', new Uuidv4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Name',
            'churchId' => 'Profile',
        ];
    }
}

