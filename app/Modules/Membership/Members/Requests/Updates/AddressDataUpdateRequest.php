<?php

namespace App\Modules\Membership\Members\Requests\Updates;

use App\Base\Http\Requests\FormRequest;
use App\Shared\Enums\StatesEnum;
use App\Shared\Rules\Uuid4Rule;

class AddressDataUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $states = implode(',', array_column(StatesEnum::cases(), 'value'));

        $stateRules = ['required', 'string', "in:$states"];

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
            'uf'            => $stateRules,
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
