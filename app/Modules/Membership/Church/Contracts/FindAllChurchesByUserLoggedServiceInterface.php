<?php

namespace App\Modules\Membership\Church\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface FindAllChurchesByUserLoggedServiceInterface
{
    public function execute(): LengthAwarePaginator|Collection;
}
