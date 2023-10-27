<?php

namespace App\Modules\Store\Products\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Modules\Store\Products\Models\Product;
use App\Shared\Rules\Uuid4Rule;

class ProductsFiltersRequest extends FormRequest
{
    public bool $requiredPagination = true;

    public array $sorting = [
        Product::PRODUCT_NAME,
        Product::VALUE,
        Product::BALANCE,
        Product::HIGHLIGHT_PRODUCT,
        Product::ACTIVE,
    ];

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'name'         => 'nullable|string',
            'categoriesId' => 'nullable|array',
            'code'         => 'nullable|string',
            'highlight'    => 'nullable|boolean',
            'active'       => 'nullable|boolean',

            'categoriesId.*' => ['nullable', new Uuid4Rule]
        ]);
    }

    public function attributes(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'         => 'Name',
            'categoriesId' => 'Categories Id',
            'code'         => 'Code',
            'highlight'    => 'Highlight',
            'active'       => 'Active',

            'categoriesId.*' => 'Categories Id',
        ]);
    }

    public function authorize(): true
    {
        return true;
    }
}
