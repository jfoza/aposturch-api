<?php

namespace App\Modules\Members\Church\Contracts;

interface ShowByChurchUniqueNameServiceInterface
{
    public function execute(string $uniqueName): object;
}
