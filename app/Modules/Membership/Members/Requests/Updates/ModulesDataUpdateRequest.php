<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;

class ModulesDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredUuid4 = ['required', new ManyUuidv4Rule()];

        return [
            'modulesId' => $requiredUuid4,
        ];
    }

    public function authorize(): array
    {
        return [
            'modulesId' => 'Modules Id',
        ];
    }
}
