<?php

namespace App\Features\General\UniqueCodePrefixes\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Enums\UniqueCodeTypesEnum;

class UniqueCodeGeneratorRequest extends FormRequest
{
    public function rules(): array
    {
        $uniqueCodeTypes = implode(',', array_column(UniqueCodeTypesEnum::cases(), 'value'));

        return [
            'uniqueCodeType' => "required|string|in:$uniqueCodeTypes",
        ];
    }

    public function attributes(): array
    {
        return [
            'uniqueCodeType' => 'Unique Code Type',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
