<?php

namespace App\Modules\Store\Categories\Contracts;

interface FindByCategoryIdServiceInterface
{
    public function execute(string $id): object;
}
