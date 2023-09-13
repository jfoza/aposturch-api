<?php

namespace App\Features\Users\Profiles\Requests;

use App\Features\Base\Http\Requests\FormRequest;

class ProfilesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'profileTypeUniqueName' => ['nullable', 'string']
        ];
    }

    public function authorize(): array
    {
        return [
            'profileTypeUniqueName' => 'Profile Type UniqueName'
        ];
    }
}
