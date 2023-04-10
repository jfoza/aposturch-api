<?php

namespace App\Modules\Members\Church\Contracts;

interface RemoveChurchServiceInterface
{
    public function execute(string $churchId);
}
