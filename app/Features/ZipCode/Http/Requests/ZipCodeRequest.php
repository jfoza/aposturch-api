<?php

namespace App\Features\ZipCode\Http\Requests;

use App\Shared\Rules\ZipCodeRule;
use App\Features\Base\Http\Requests\FormRequest;

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
