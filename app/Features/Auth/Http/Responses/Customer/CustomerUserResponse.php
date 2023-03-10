<?php

namespace App\Features\Auth\Http\Responses\Customer;

class CustomerUserResponse
{
    public string|null $id;
    public string|null $email;
    public string|null $avatar;
    public string|null $fullName;
    public object|null $role;
    public bool|null   $status;

    public string|null $phone;
    public string|null $zipCode;
    public string|null $address;
    public string|null $numberAddress;
    public string|null $complement;
    public string|null $district;
    public object|null $city;
}
