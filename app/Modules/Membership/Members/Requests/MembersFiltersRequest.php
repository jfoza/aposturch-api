<?php

namespace App\Modules\Membership\Members\Requests;

use App\Features\Base\Http\Requests\FormRequest;
use App\Shared\Rules\ManyUuidv4Rule;
use App\Shared\Rules\Uuidv4Rule;

class MembersFiltersRequest extends FormRequest
{

    public function rules(): array
    {
        return $this->mergePaginationOrderRules([
            'churchIds' => ['nullable', new ManyUuidv4Rule()],
            'profileId' => ['nullable', 'string', new Uuidv4Rule()],
            'cityId'    => ['nullable', 'string', new Uuidv4Rule()],
            'name'      => 'nullable|string'
        ]);
    }

    public function authorize(): array
    {
        return $this->mergePaginationOrderAttributes([
            'churchIds' => 'Church Ids',
            'profileId' => 'Profile Id',
            'cityId'    => 'City Id',
            'name'      => 'Name',
        ]);
    }
}
