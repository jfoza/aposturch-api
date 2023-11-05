<?php

namespace App\Modules\Store\Categories\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class CategoriesUpdateStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'categoriesId' => ['required', 'array', 'max:100', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'categoriesId' => 'Categories Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
