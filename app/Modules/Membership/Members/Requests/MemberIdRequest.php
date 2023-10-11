<?php

namespace App\Modules\Membership\Members\Requests;

use App\Base\Http\Requests\FormRequest;

class MemberIdRequest extends FormRequest
{
    public function rules(): array
    {

        return [
            'isUpdate' => 'nullable|boolean',
        ];
    }

    public function authorize(): array
    {
        return [
            'isUpdate' => 'Is Update',
        ];
    }
}
