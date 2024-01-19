<?php

namespace App\Features\Users\Users\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\NoSpecialCharactersRule;
use App\Shared\Rules\Uuid4Rule;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => ['required', 'string', new NoSpecialCharactersRule],
            'email'                => 'required|email',
            'password'             => 'required|string',
            'passwordConfirmation' => 'required|same:password',
            'profileId'            => ['string', 'required', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'Email',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password confirmation',
            'profileId'            => 'Profile',
        ];
    }
}

