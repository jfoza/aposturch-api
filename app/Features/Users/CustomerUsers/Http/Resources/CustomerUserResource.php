<?php

namespace App\Features\Users\CustomerUsers\Http\Resources;

use App\Features\Users\CustomerUsers\Http\Responses\CustomerUserResponse;

class CustomerUserResource
{
    public function __construct(
        public CustomerUserResponse $customerUserResponse,
    ) {}

    /**
     * @return CustomerUserResponse
     */
    public function getCustomerUserResponse(): CustomerUserResponse
    {
        return $this->customerUserResponse;
    }

    /**
     * @param object $person
     * @param object $user
     */
    public function setCustomerUserResponse(object $person, object $user): void
    {
        $this->customerUserResponse->id            = $user->id;
        $this->customerUserResponse->name          = $user->name;
        $this->customerUserResponse->email         = $user->email;
        $this->customerUserResponse->active        = $user->active;
        $this->customerUserResponse->phone         = $person->phone;
        $this->customerUserResponse->zipCode       = $person->zip_code;
        $this->customerUserResponse->address       = $person->address;
        $this->customerUserResponse->numberAddress = $person->number_address;
        $this->customerUserResponse->complement    = $person->complement;
        $this->customerUserResponse->district      = $person->district;
        $this->customerUserResponse->cityId        = $person->city_id;
        $this->customerUserResponse->uf            = $person->uf;
    }
}
