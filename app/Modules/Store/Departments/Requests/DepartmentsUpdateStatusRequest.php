<?php

namespace App\Modules\Store\Departments\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class DepartmentsUpdateStatusRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'departmentsId' => ['required', 'array', 'max:100', new ManyUuidv4Rule()],
        ];
    }

    public function attributes(): array
    {
        return [
            'departmentsId' => 'Departments Id',
        ];
    }

    public function authorize(): true
    {
        return true;
    }
}
