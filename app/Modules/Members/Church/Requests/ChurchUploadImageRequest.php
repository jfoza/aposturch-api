<?php

namespace App\Modules\Members\Church\Requests;

use App\Shared\Rules\Uuidv4Rule;
use App\Features\Base\Http\Requests\FormRequest;

class ChurchUploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|max:1024',
            'churchId' => ['required', new Uuidv4Rule]
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'Image',
            'churchId' => 'Church Id'
        ];
    }
}

