<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class ChurchDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredUuid4 = ['string', 'required', new Uuid4Rule];

        return [
            'churchId' => $requiredUuid4,
        ];
    }

    public function authorize(): array
    {
        return [
            'churchId' => 'Church Id',
        ];
    }
}
