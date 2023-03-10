<?php

namespace App\Features\Users\CustomerUsers\Http\Responses;

class CustomerUserResponse
{
    public string $id;
    public string|null $personId;
    public string $name;
    public string $email;
    public string $profileId;
    public string $phone;
    public string $zipCode;
    public string $address;
    public string $numberAddress;
    public string|null $complement;
    public string $district;
    public string $cityId;
    public string $uf;
    public bool $active;
}
