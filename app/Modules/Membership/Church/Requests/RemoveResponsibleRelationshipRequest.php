<?php

namespace App\Modules\Membership\Church\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuidv4Rule;

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

