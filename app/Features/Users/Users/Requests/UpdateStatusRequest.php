<?php

namespace App\Features\Users\Users\Requests;

use App\Base\Http\Requests\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => 'required|boolean',
        ];
    }

    public function attributes(): array
    {
        return [
            'status' => 'Status',
        ];
    }
}
