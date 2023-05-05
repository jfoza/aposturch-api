<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Modules\Membership\Church\DTO\ChurchDTO;

interface CreateChurchServiceInterface
{
    public function execute(ChurchDTO $churchDTO): object;
}
