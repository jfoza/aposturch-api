<?php

namespace App\Modules\Membership\Church\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

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
            'churchId' => ['required', new Uuid4Rule]
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

