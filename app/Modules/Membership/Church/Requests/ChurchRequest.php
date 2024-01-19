<?php

namespace App\Modules\Membership\Church\Requests;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Rules\NoSpecialCharactersRule;
use App\Shared\Rules\StatesRule;
use App\Shared\Rules\Uuid4Rule;

class ChurchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nullableString = 'nullable|string';
        $nullableStringEmail = 'nullable|email';
        $requiredString = 'required|string';
        $requiredBoolean = 'required|bool';

        $namesRules = ['required', 'string', new NoSpecialCharactersRule];

        return [
            'name'               => $namesRules,
            'phone'              => $nullableString.'|min:10|max:11',
            'email'              => $nullableStringEmail,
            'youtube'            => $nullableString,
            'facebook'           => $nullableString,
            'instagram'          => $nullableString,
            'zipCode'            => $requiredString.'|size:8',
            'address'            => $requiredString,
            'numberAddress'      => $requiredString,
            'complement'         => $nullableString,
            'district'           => $requiredString,
            'uf'                 => ['required', 'string', new StatesRule],
            'cityId'             => ['required', 'string', new Uuid4Rule],
            'active'             => $requiredBoolean,
        ];
    }

    public function attributes(): array
    {
        return [
            'name'               => 'Name',
            'responsibleMembers' => 'Responsible',
            'phone'              => 'Phone',
            'email'              => 'E-mail',
            'youtube'            => 'YouTube',
            'facebook'           => 'Facebook',
            'instagram'          => 'Instagram',
            'zipCode'            => 'Zip Code',
            'address'            => 'Address',
            'numberAddress'      => 'Number Address',
            'complement'         => 'Complement',
            'district'           => 'District',
            'uf'                 => 'UF',
            'cityId'             => 'City Id',
        ];
    }
}
