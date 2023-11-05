<?php

namespace App\Modules\Membership\Members\Contracts\Updates;

use App\Modules\Membership\Members\DTO\GeneralDataUpdateDTO;
use App\Modules\Membership\Members\Responses\UpdateMemberResponse;

interface GeneralDataUpdateServiceInterface
{
    public function execute(GeneralDataUpdateDTO $generalDataUpdateDTO): UpdateMemberResponse;
}
