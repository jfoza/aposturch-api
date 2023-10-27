<?php

namespace App\Modules\Store\Products\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Enums\HttpRequestMethodsEnum;
use App\Shared\Rules\ProductCodeRule;
use App\Shared\Rules\Uuid4Rule;

class ProductsRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'productName'        => 'required|string',
            'productDescription' => 'nullable|string',
            'productCode'        => ['required', 'string'],
            'value'              => 'required|decimal:0,2|min:0',
            'quantity'           => 'required|integer|min:0',
            'balance'            => 'nullable|integer|min:0',
            'highlightProduct'   => 'required|boolean',
            'categoriesId'       => 'nullable|array',

            'categoriesId.*'  => ['nullable', new Uuid4Rule]
        ];

        if($this->method() == HttpRequestMethodsEnum::PUT->value)
        {
            $rules['balance'] = 'required|integer|min:0|lte:quantity';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'productName'        => 'Product Name',
            'productDescription' => 'Product Description',
            'value'              => 'Value',
            'quantity'           => 'Quantity',
            'highlightProduct'   => 'Highlight Product',
            'categoriesId'       => 'Categories Id',
            'categoriesId.*'     => 'Categories Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
