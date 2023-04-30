<?php

namespace App\Modules\Membership\Church\Contracts;

interface ShowByChurchUniqueNameServiceInterface
{
    public function execute(string $churchUniqueName): object;
}
