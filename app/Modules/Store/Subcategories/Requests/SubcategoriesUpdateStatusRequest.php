<?php

namespace App\Modules\Store\Subcategories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class SubcategoriesUpdateStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subcategoriesId' => ['required', 'array', 'max:100', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'subcategoriesId' => 'Subcategories Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
