<?php

namespace App\Modules\Membership\Church\Contracts;

use App\Modules\Membership\Church\DTO\ChurchDTO;
use App\Modules\Membership\Church\Models\Church;
use Illuminate\Support\Collection;

interface CreateChurchServiceInterface
{
    public function execute(ChurchDTO $churchDTO): Church|Collection;
}
