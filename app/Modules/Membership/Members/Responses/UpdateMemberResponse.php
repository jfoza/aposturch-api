<?php

namespace App\Modules\Membership\Members\Responses;

class UpdateMemberResponse
{
    public ?string $id;
    public ?string $name;
    public ?string $email;
    public ?bool $active;
    public ?string $phone;
    public ?string $zipCode;
    public ?string $address;
    public ?string $numberAddress;
    public ?string $complement;
    public ?string $district;
    public ?string $cityId;
    public ?string $uf;

    public ?string $churchId;
    public ?string $profileId;
    public ?array $modulesId;
}
