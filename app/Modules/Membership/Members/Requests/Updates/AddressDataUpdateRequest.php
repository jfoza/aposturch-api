<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class AddressDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $requiredString = 'required|string';
        $nullableString = 'nullable|string';
        $requiredUuid4 = ['string', 'required', new Uuid4Rule];

        return [
            'zipCode'       => $requiredString,
            'address'       => $requiredString,
            'numberAddress' => $requiredString,
            'complement'    => $nullableString,
            'district'      => $requiredString,
            'cityId'        => $requiredUuid4,
            'uf'            => $requiredString,
        ];
    }

    public function authorize(): array
    {
        return [
            'zipCode'       => 'Zip Code',
            'address'       => 'Address',
            'numberAddress' => 'Number Address',
            'complement'    => 'Complement',
            'district'      => 'District',
            'cityId'        => 'City Id',
            'uf'            => 'UF',
        ];
    }
}
