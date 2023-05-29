<?php

namespace App\Modules\Membership\Church\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class ChurchFiltersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nullableString = 'nullable|string';

        return $this->mergePaginationOrderRules([
            'name'   => $nullableString,
            'cityId' => ['nullable', 'string', new Uuid4Rule],
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'   => 'Person Name',
            'cityId' => 'City Id',
        ]);
    }
}
