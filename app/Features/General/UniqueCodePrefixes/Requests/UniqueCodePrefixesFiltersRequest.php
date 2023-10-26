<?php

namespace App\Features\General\UniqueCodePrefixes\Requests;

use App\Base\Http\Requests\FormRequest;

class UniqueCodePrefixesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'prefix' => 'nullable|string',
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'prefix' => 'Prefix',
        ]);
    }

    public function authorize(): bool
    {
        return true;
    }
}
