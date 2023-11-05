<?php

namespace App\Modules\Membership\Members\DTO;

class GeneralDataUpdateDTO
{
    public ?string $id;
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?bool   $active;
}
