<?php

namespace App\Modules\Membership\Church\Contracts;

interface RemoveChurchServiceInterface
{
    public function execute(string $churchId);
}
