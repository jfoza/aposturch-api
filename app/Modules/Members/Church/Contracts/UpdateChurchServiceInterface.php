<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\DTO\ChurchDTO;

interface UpdateChurchServiceInterface
{
    public function execute(ChurchDTO $churchDTO);
}
