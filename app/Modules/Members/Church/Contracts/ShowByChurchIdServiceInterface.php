<?php

namespace App\Modules\Members\Church\Contracts;

interface ShowByChurchIdServiceInterface
{
    public function execute(string $churchId): object;
}
