<?php

namespace App\Modules\Membership\Church\Contracts;

interface ShowByChurchIdServiceInterface
{
    public function execute(string $churchId): object;
}
