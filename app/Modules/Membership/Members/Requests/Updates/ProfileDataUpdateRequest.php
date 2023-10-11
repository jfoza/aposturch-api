<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class ProfileDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredUuid4 = ['string', 'required', new Uuid4Rule];

        return [
            'profileId' => $requiredUuid4,
        ];
    }

    public function authorize(): array
    {
        return [
            'profileId' => 'Church Id',
        ];
    }
}
