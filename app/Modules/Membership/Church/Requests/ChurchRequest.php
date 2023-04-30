<?php

namespace App\Modules\Membership\Church\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;
use App\Shared\Rules\StatesRule;
use App\Shared\Rules\Uuidv4Rule;

class ChurchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $nullableString = 'nullable|string';
        $requiredString = 'required|string';
        $requiredBoolean = 'required|bool';

        return [
            'name'           => $requiredString,
            'responsibleIds' => ['required', 'array', new ManyUuidv4Rule()],
            'phone'          => $nullableString.'|min:10|max:11',
            'email'          => $nullableString,
            'youtube'        => $nullableString,
            'facebook'       => $nullableString,
            'instagram'      => $nullableString,
            'zipCode'        => $requiredString.'|size:8',
            'address'        => $requiredString,
            'numberAddress'  => $requiredString,
            'complement'     => $nullableString,
            'district'       => $requiredString,
            'active'         => $requiredBoolean,
            'uf'             => ['required', 'string', new StatesRule],
            'cityId'         => ['required', 'string', new Uuidv4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'           => 'Name',
            'responsibleIds' => 'Responsible',
            'phone'          => 'Phone',
            'email'          => 'E-mail',
            'youtube'        => 'YouTube',
            'facebook'       => 'Facebook',
            'instagram'      => 'Instagram',
            'zipCode'        => 'Zip Code',
            'address'        => 'Address',
            'numberAddress'  => 'Number Address',
            'complement'     => 'Complement',
            'district'       => 'District',
            'uf'             => 'UF',
            'cityId'         => 'City Id',
        ];
    }
}
