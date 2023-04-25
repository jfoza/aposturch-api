<?php

namespace App\Modules\Members\Church\DTO;

class ChurchDTO
{
    public string|null $id;
    public string|null $name;
    public array $responsibleIds;
    public string|null $uniqueName;
    public string|null $phone;
    public string|null $email;
    public string|null $youtube;
    public string|null $facebook;
    public string|null $instagram;
    public string|null $zipCode;
    public string|null $address;
    public string|null $numberAddress;
    public string|null $complement;
    public string|null $district;
    public string|null $uf;
    public string|null $cityId;
    public bool $active;
}
