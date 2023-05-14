<?php

namespace App\Modules\Membership\Members\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuidv4Rule;

class MembersRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = 'required|string';
        $nullableString = 'nullable|string';
        $requiredUuid4 = ['string', 'required', new Uuidv4Rule];

        return [
            'name'                 => $requiredString,
            'email'                => 'required|email:rfc,dns',
            'password'             => $requiredString,
            'passwordConfirmation' => 'required|same:password',
            'active'               => 'required|bool',
            'profileId'            => $requiredUuid4,
            'churchId'             => $requiredUuid4,
            'phone'                => $requiredString,
            'zipCode'              => $requiredString,
            'address'              => $requiredString,
            'numberAddress'        => $requiredString,
            'complement'           => $nullableString,
            'district'             => $requiredString,
            'cityId'               => $requiredUuid4,
            'uf'                   => $requiredString,
        ];
    }

    public function authorize(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'Email',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password confirmation',
            'active'               => 'Active',
            'profileId'            => 'Profile',
            'churchId'             => 'Church Id',
            'phone'                => 'Phone',
            'zipCode'              => 'Zip Code',
            'address'              => 'Address',
            'numberAddress'        => 'Number Address',
            'complement'           => 'Complement',
            'district'             => 'District',
            'cityId'               => 'City Id',
            'uf'                   => 'UF',
        ];
    }
}
