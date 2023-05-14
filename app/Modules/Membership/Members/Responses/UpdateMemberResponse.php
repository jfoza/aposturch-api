<?php

namespace App\Modules\Membership\Members\Responses;

class UpdateMemberResponse
{
    public function __construct(
        public string $id,
        public string $name,
        public string $email,
        public string $phone,
        public string $zipCode,
        public string $address,
        public string $numberAddress,
        public ?string $complement,
        public string $district,
        public string $cityId,
        public string $uf,
    ) {}
}
