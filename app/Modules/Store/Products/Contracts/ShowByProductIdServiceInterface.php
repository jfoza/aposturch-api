<?php

namespace App\Modules\Store\Products\Contracts;

interface ShowByProductIdServiceInterface
{
    public function execute(string $id): object;
}
