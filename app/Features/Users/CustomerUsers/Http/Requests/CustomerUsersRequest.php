<?php

namespace App\Features\Users\CustomerUsers\Http\Requests;

use App\Shared\Rules\PhoneRule;
use App\Shared\Rules\Uuid4Rule;
use App\Shared\Rules\ZipCodeRule;
use App\Features\Base\Http\Requests\FormRequest;

class CustomerUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $requiredString = 'required|string';

        return [
            'name'                 => $requiredString,
            'email'                => 'required|email:rfc,dns',
            'active'               => 'required|bool',
            'phone'                => ['required', new PhoneRule],
            'zipCode'              => ['required', new ZipCodeRule],
            'address'              => $requiredString,
            'numberAddress'        => $requiredString,
            'complement'           => 'nullable|string',
            'district'             => $requiredString,
            'cityId'               => ['required', new Uuid4Rule],
            'uf'                   => $requiredString,
        ];
    }

    public function attributes(): array
    {
        return [
            'name'                 => 'Name',
            'email'                => 'Email',
            'active'               => 'Active',
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
