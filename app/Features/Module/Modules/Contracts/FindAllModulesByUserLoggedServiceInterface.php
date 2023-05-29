<?php

namespace App\Features\Module\Modules\Contracts;

use Illuminate\Support\Collection;

interface FindAllModulesByUserLoggedServiceInterface
{
    public function execute(): Collection;
}
