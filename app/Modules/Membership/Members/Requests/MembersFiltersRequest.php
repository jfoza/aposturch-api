<?php

namespace App\Modules\Membership\Members\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class MembersFiltersRequest extends FormRequest
{

    public function rules(): array
    {
        $nullableString = 'nullable|string';
        $nullableUuid4 = ['nullable', new Uuid4Rule()];

        return $this->mergePaginationOrderRules([
            'name'      => $nullableString,
            'phone'     => $nullableString,
            'email'     => $nullableString,
            'churchId'  => $nullableUuid4,
            'profileId' => $nullableUuid4,
            'cityId'    => $nullableUuid4,
        ]);
    }

    public function authorize(): array
    {
        return $this->mergePaginationOrderAttributes([
            'name'      => 'Name',
            'phone'     => 'Phone',
            'email'     => 'Email',
            'churchId'  => 'Church Id',
            'profileId' => 'Profile Id',
            'cityId'    => 'City Id',
        ]);
    }
}
