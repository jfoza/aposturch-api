<?php

namespace App\Modules\Members\Church\Requests;

use App\Shared\Rules\Uuidv4Rule;
use App\Features\Base\Http\Requests\FormRequest;

class RemoveResponsibleRelationshipRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'churchId' => ['required', new Uuidv4Rule]
        ];
    }

    public function attributes(): array
    {
        return [
            'churchId' => 'Church Id'
        ];
    }
}

