<?php

namespace App\Features\ZipCode\Http\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ZipCodeRule;

class ZipCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'zipCode' => ['required', new ZipCodeRule],
        ];
    }

    public function attributes(): array
    {
        return [
            'zipCode' => 'Zip Code',
        ];
    }
}
