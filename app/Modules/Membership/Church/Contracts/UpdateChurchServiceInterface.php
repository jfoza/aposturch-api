<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Modules\Membership\Church\DTO\ChurchDTO;

interface UpdateChurchServiceInterface
{
    public function execute(ChurchDTO $churchDTO);
}
