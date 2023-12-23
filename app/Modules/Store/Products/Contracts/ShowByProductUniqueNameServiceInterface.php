<?php

namespace App\Modules\Store\Products\Contracts;

interface ShowByProductUniqueNameServiceInterface
{
    public function execute(string $uniqueName): object;
}
