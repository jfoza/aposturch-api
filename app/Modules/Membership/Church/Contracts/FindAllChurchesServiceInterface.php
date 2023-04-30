<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Modules\Membership\Church\DTO\ChurchFiltersDTO;

interface FindAllChurchesServiceInterface
{
    public function execute(ChurchFiltersDTO $churchFiltersDTO);
}
