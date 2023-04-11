<?php

namespace App\Features\ZipCode\Http\Responses;

class ZipCodeResponse
{
    public string|null $zipCode;
    public string|null $address;
    public string|null $district;
    public object|null $city;
    public array|null $citiesByUF;
}
