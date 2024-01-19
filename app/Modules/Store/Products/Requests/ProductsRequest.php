<?php

namespace App\Modules\Store\Products\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Enums\HttpRequestMethodsEnum;
use App\Shared\Rules\Uuid4Rule;

class ProductsRequest extends FormRequest
{
    public function rules(): array
    {
        $namesRules = ['required', 'string'];

        $rules = [
            'productName'        => $namesRules,
            'productDescription' => 'nullable|string',
            'productCode'        => ['required', 'string'],
            'value'              => 'required|decimal:0,2|min:0',
            'quantity'           => 'required|integer|min:0',
            'balance'            => 'nullable|integer|min:0',
            'highlightProduct'   => 'required|boolean',
            'categoriesId'       => 'nullable|array',
            'imageLinks'         => 'nullable|array|max:3',

            'categoriesId.*'  => ['nullable', new Uuid4Rule],
            'imageLinks.*'    => ['nullable', 'url'],
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
            'imageLinks'         => 'Image Links',

            'categoriesId.*'     => 'Categories Id',
            'imageLinks.*'       => 'Image Links',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
