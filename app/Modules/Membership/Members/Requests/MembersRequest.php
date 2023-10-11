<?php

namespace App\Modules\Membership\Members\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Enums\StatesEnum;
use App\Shared\Rules\ManyUuidv4Rule;
use App\Shared\Rules\Uuid4Rule;

class MembersRequest extends FormRequest
{
    public function rules(): array
    {
        $states = implode(',', array_column(StatesEnum::cases(), 'value'));

        $stateRules = ['required', 'string', "in:$states"];

        $requiredString = 'required|string';
        $nullableString = 'nullable|string';
        $requiredUuid4 = ['string', 'required', new Uuid4Rule];
        $requiredManyUuid4 = ['required', new ManyUuidv4Rule];

        return [
            'name'                 => $requiredString,
            'email'                => 'required|email',
            'password'             => $requiredString,
            'passwordConfirmation' => 'required|same:password',
            'profileId'            => $requiredUuid4,
            'modulesId'            => $requiredManyUuid4,
            'churchId'             => $requiredUuid4,
            'phone'                => $requiredString,
            'zipCode'              => $requiredString,
            'address'              => $requiredString,
            'numberAddress'        => $requiredString,
            'complement'           => $nullableString,
            'district'             => $requiredString,
            'cityId'               => $requiredUuid4,
            'uf'                   => $stateRules,
        ];
    }

    public function authorize(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'Email',
            'password'             => 'Password',
            'passwordConfirmation' => 'Password confirmation',
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
