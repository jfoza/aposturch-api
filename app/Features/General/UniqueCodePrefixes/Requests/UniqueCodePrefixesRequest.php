<?php

namespace App\Features\General\UniqueCodePrefixes\Requests;

use App\Base\Http\Requests\FormRequest;

class UniqueCodePrefixesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'prefix' => 'required|string',
            'active' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'prefix' => 'Prefix',
            'active' => 'Active',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
