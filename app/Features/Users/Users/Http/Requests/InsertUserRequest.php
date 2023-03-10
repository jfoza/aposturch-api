<?php

namespace App\Features\Users\Users\Http\Requests;

use App\Shared\Rules\Uuidv4Rule;
use App\Features\Base\Http\Requests\FormRequest;

class InsertUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'                 => 'required|string',
            'email'                => 'required|email:rfc,dns',
            'password'             => 'required|string',
            'passwordConfirmation' => 'required|same:password',
            'active'               => 'required|bool',
            'profileId'            => ['string', 'required', new Uuidv4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'Email',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password confirmation',
            'active'               => 'Active',
            'profileId'            => 'Profile',
        ];
    }
}

