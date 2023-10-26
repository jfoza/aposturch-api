<?php

namespace App\Modules\Store\Products\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class ProductsUpdateStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'productsId' => ['required', 'array', 'max:100', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'productsId' => 'Products Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
