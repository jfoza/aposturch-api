<?php

namespace App\Features\Users\Users\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class UsersUploadImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'required|image|max:1024',
            'userId' => ['required', new Uuid4Rule]
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'Image',
            'userId' => 'User Id'
        ];
    }
}

