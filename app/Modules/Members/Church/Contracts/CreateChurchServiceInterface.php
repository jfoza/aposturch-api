<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\DTO\ChurchDTO;
use App\Modules\Members\Church\Models\Church;
use Illuminate\Support\Collection;

interface CreateChurchServiceInterface
{
    public function execute(ChurchDTO $churchDTO): Church|Collection;
}
