<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Features\Base\Http\Requests\FormRequest;

class GeneralDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = 'required|string';

        return [
            'name'   => $requiredString,
            'email'  => 'required|email:rfc,dns',
            'phone'  => $requiredString,
        ];
    }

    public function authorize(): array
    {
        return [
            'name'   => 'Name',
            'email'  => 'Email',
            'phone'  => 'Phone',
        ];
    }
}
