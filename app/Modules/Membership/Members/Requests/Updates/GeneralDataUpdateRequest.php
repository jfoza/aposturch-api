<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\NoSpecialCharactersRule;

class GeneralDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = ['required', 'string', new NoSpecialCharactersRule];

        return [
            'name'   => $requiredString,
            'email'  => 'required|email',
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
