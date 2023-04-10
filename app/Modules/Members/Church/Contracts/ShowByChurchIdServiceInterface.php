<?php

namespace App\Modules\Members\Church\Contracts;

use App\Modules\Members\Church\Models\Church;

interface ShowByChurchIdServiceInterface
{
    public function execute(string $churchId): ?Church;
}
