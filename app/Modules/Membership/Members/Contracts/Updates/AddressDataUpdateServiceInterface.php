<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Modules\Membership\Members\DTO\AddressDataUpdateDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface AddressDataUpdateServiceInterface
{
    public function execute(AddressDataUpdateDTO $addressDataUpdateDTO): UpdateMemberResponse;
}
