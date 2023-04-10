<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\DTO\ChurchFiltersDTO;

interface FindAllChurchesServiceInterface
{
    public function execute(ChurchFiltersDTO $churchFiltersDTO);
}
